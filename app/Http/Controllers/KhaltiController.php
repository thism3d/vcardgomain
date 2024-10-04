<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\PlanOrder;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Exception;
use App\Khalti\Khalti;

class KhaltiController extends Controller
{
    public function planPayWithKhalti(Request $request)
    {
        $planID = Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $currency = !empty($adminPaymentSettings['CURRENCY']) ? $adminPaymentSettings['CURRENCY'] : 'USD';
        config(
            [
                'khalti.public_key' => isset($adminPaymentSettings['khalti_public_key']) ? $adminPaymentSettings['khalti_public_key'] : '',
                'khalti.sck' => isset($adminPaymentSettings['khalti_secret_key']) ? $adminPaymentSettings['khalti_secret_key'] : '',
            ]
        );
        $plan = Plan::find($planID);
        $coupon_id = '0';

        $coupon_code = null;
        $discount_value = null;
        $coupons = Coupon::where('code', $request->coupon_code)->where('is_active', '1')->first();
        if ($plan) {
            $price = $plan->price;
            if (isset($request->coupon_code) && !empty($request->coupon_code)) {
                $request->coupon_code = trim($request->coupon_code);
                $coupons = Coupon::where('code', strtoupper($request->coupon_code))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if ($usedCoupun >= $coupons->limit) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                    $coupon_id = $coupons->id;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            if ($price <= 0) {
                $authuser = Auth::user();
                $authuser->plan = $plan->id;
                $authuser->save();
                $assignPlan = $authuser->assignPlan($plan->id);
                if ($assignPlan['is_success'] == true && !empty($plan)) {
                    if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                        try {
                            $authuser->cancel_subscription($authuser->id);
                        } catch (Exception $e) {
                            return redirect()->route('plans.index')->with('error', __($e->getMessage()));
                        }
                    }
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $authuser->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                    PlanOrder::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => $currency,
                            'txn_id' => '',
                            'payment_type' => 'Tap',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id);
                    $amount = $price;
                    return $amount;
                    //return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                }
            }
            $secret = !empty($adminPaymentSettings['khalti_secret_key']) ? $adminPaymentSettings['khalti_secret_key'] : '';
            $amount = $price;
            return $amount;

        } else {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }
    public function planGetKhaltiStatus(Request $request)
    {

        $admin_settings = Utility::getAdminPaymentSetting();
        $currency = isset($admin_settings['currency']) ? $admin_settings['currency'] : 'USD';
        $plan_id = decrypt($request->plan_id);
        $plan = Plan::find($plan_id);
        $user = \Auth::user();

        $payload = $request->payload;
       
        $secret = !empty($admin_settings['khalti_secret_key']) ? $admin_settings['khalti_secret_key'] : '';
        $token = $payload['token'];
        $amount = $payload['amount'];
        $khalti = new Khalti();
        $response = $khalti->verifyPayment($secret, $token, $amount);

        Utility::referralTransaction($plan);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        try {
            if ($response['status_code'] == '200') {
                $order = new PlanOrder();
                $order->order_id = $orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $amount/100;
                $order->price_currency = $currency;
                $order->txn_id = time();
                $order->payment_type = __('Khalti');
                $order->payment_status = 'success';
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();
                $user = User::find($user->id);

                $coupons = Coupon::where('code', $request->coupon_code)->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $order->order_id;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();

                }
                $assignPlan = $user->assignPlan($plan->id);

                if ($assignPlan['is_success']) {
                    return $response;
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
            }
        } catch (Exception $e) {
            return response()->json('failed');
        }
    }

}
