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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Exception;

class FedaPayController extends Controller
{
    public function planPayWithFedapay(Request $request)
    {
        $planID = Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $currency = !empty($adminPaymentSettings['CURRENCY']) ? $adminPaymentSettings['CURRENCY'] : 'USD';
        $plan = Plan::find($planID);
        $coupon_id = '0';
        $price = $plan->price;
        $coupon_code = null;
        $discount_value = null;
        $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
        if ($coupons) {
            $coupon_code = $coupons->code;
            $usedCoupun = $coupons->used_coupon();
            if ($coupons->limit == $usedCoupun) {
                $res_data['error'] = __('This coupon code has expired.');
            } else {
                $discount_value = ($plan->price / 100) * $coupons->discount;
                $price = $price - $discount_value;
                if ($price < 0) {
                    $price = $plan->price;
                }
                $coupon_id = $coupons->id;
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
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => $currency,
                            'txn_id' => '',
                            'payment_type' => 'Fedapay',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id);

                    return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                }
            }
        }
        try {

            $fedapay = !empty($adminPaymentSettings['fedapay_secret_key']) ? $adminPaymentSettings['fedapay_secret_key'] : '';
            $fedapay_mode = !empty($adminPaymentSettings['fedapay_mode']) ? $adminPaymentSettings['fedapay_mode'] : 'sandbox';
            \FedaPay\FedaPay::setApiKey($fedapay);

            \FedaPay\FedaPay::setEnvironment($fedapay_mode);
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $transaction = \FedaPay\Transaction::create([
                "description" => "Fedapay Payment",
                "amount" => $price,
                "currency" => ["iso" => $currency],

                "callback_url" => route('plan.get.fedapay.status', [
                    'order_id' => $orderID,
                    'plan_id' => $plan->id,
                    'coupon_code' => $request->coupon,
                    "get_amount" => $price,
                ]),
                "cancel_url" => route('plan.get.fedapay.status', [
                    'order_id' => $orderID,
                    'plan_id' => $plan->id,
                    'coupon_code' => $request->coupon,
                    "get_amount" => $price,
                ]),

            ]);
            $token = $transaction->generateToken();

            return redirect($token->url);

        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', $e->getMessage());
        }

    }
    public function planGetFedapayStatus(Request $request, $plan_id)
    {
        if ($request->status == 'approved') {
            $payment_setting = Utility::getAdminPaymentSetting();
            $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            $getAmount = $request->get_amount;
            $user = \Auth::user();
            $plan = Plan::find($request->plan_id);
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
            $order->payment_type = __('Fedapay');
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
            Utility::referralTransaction($plan);
            $assignPlan = $user->assignPlan($plan->id);


            if ($assignPlan['is_success']) {
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
            } else {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }
        } else {
            return redirect()->route('plans.index')->with('error', 'Transaction has been failed');
        }
    }

}
