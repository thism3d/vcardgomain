<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanOrder;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Utility;
use Lahirulhr\PayHere\PayHere;

class PayHereController extends Controller
{
    public function planPayWithPayHere(Request $request)
    {
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $currency = !empty($admin_payment_setting['CURRENCY']) ? $admin_payment_setting['CURRENCY'] : 'USD';
        $objUser = \Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);

        if ($plan) {
            $get_amount = $plan->price;
           
                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $get_amount = $plan->price - $discount_value;

                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        if ($get_amount <= 0) {
                            $authuser = \Auth::user();
                            $authuser->plan = $plan->id;
                            $authuser->save();
                            $assignPlan = $authuser->assignPlan($plan->id);
                            if ($assignPlan['is_success'] == true && !empty($plan)) {
                                if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                                    try {
                                        $authuser->cancel_subscription($authuser->id);
                                    } catch (\Exception $exception) {
                                        \Log::debug($exception->getMessage());
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
                                        'price' => $get_amount == null ? 0 : $get_amount,
                                        'price_currency' => !empty($admin_payment_setting['CURRENCY']) ? $admin_payment_setting['CURRENCY'] : 'USD',
                                        'txn_id' => '',
                                        'payment_type' => 'PayHere',
                                        'payment_status' => 'success',
                                        'receipt' => null,
                                        'user_id' => $authuser->id,
                                    ]
                                );
                                $assignPlan = $authuser->assignPlan($plan->id);
                                return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                            }
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                try {

                    $config = [
                        'payhere.api_endpoint' => $admin_payment_setting['payhere_mode'] === 'sandbox'
                            ? 'https://sandbox.payhere.lk/'
                            : 'https://www.payhere.lk/',
                    ];
    
                    $config['payhere.merchant_id'] = $admin_payment_setting['payhere_merchant_id'] ?? '';
                    $config['payhere.merchant_secret'] = $admin_payment_setting['payhere_merchant_secret'] ?? '';
                    $config['payhere.app_secret'] = $admin_payment_setting['payhere_app_secret'] ?? '';
                    $config['payhere.app_id'] = $admin_payment_setting['payhere_app_id'] ?? '';
                    config($config);
    
                    $hash = strtoupper(
                        md5(
                            $admin_payment_setting['payhere_merchant_id'] .
                                $orderID .
                                number_format($get_amount, 2, '.', '') .
                                'LKR' .
                                strtoupper(md5($admin_payment_setting['payhere_merchant_secret']))
                        )
                    );
    
                    $data = [
                        'first_name' => $objUser->name,
                        'last_name' => '',
                        'email' => $objUser->email,
                        'phone' => '',
                        'address' => 'Main Rd',
                        'city' => 'Anuradhapura',
                        'country' => 'Sri lanka',
                        'order_id' => $orderID,
                        'items' => $plan->name,
                        'currency' => 'LKR',
                        'amount' => $get_amount,
                        'hash' => $hash,
                    ];
    
    
                    return PayHere::checkOut()
                        ->data($data)
                        ->successUrl(route('plan.get.payhere.status', [
                            $plan->id,
                            'amount' => $get_amount,
                            'coupon_code' => $request->coupon,
                        ]))
                        ->failUrl(route('plan.get.payhere.status', [
                            $plan->id,
                            'amount' => $get_amount,
                            'coupon_code' => $request->coupon,
                        ]))
                        ->renderView();
                } catch (\Exception $e) {
                    \Log::debug($e->getMessage());
                    return redirect()->route('plans.index')->with('error', $e->getMessage());
                }
           
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }
    public function planGetPayHereStatus(Request $request, $plan_id)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->get_amount;
        $user = \Auth::user();
        $plan = Plan::find($request->plan);
            Utility::referralTransaction($plan);

        $order = new PlanOrder();
        $order->order_id = time();
        $order->name = $user->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $getAmount;
        $order->price_currency = $currency;
        $order->txn_id = time();
        $order->payment_type = __('PayHere');
        $order->payment_status = 'success';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();
        $coupons = Coupon::where('id', $request->coupon_id)->where('is_active', '1')->first();
        if (!empty($coupons)) {
            $userCoupon = new UserCoupon();
            $userCoupon->user = $user->id;
            $userCoupon->coupon = $coupons->id;
            $userCoupon->order = $order->order_id;
            $userCoupon->save();
            $usedCoupun = $coupons->used_coupon();
            
        }
        Utility::referralTransaction($plan);
        $assignPlan = $user->assignPlan($plan->id);


        if ($assignPlan['is_success']) {
            return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
        } else {
            return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
        }

    }
}
