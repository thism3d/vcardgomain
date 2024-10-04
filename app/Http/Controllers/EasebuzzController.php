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
use Easebuzz\Easebuzz;

class EasebuzzController extends Controller
{
    public function planPayWithEasebuzz(Request $request)
    {
        $planID = Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $currency = !empty($adminPaymentSettings['CURRENCY']) ? $adminPaymentSettings['CURRENCY'] : 'USD';
        $plan = Plan::find($planID);
        $coupon_id = '0';
        
        $discount_value = null;
        $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
        if($plan)
        {
            $price = $plan->price;
            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    if ($coupons->type == 'percentage') {
                        $discount_value = ($price / 100) * $coupons->discount;
                    } else {
                        $discount_value = $coupons->discount;
                    }
                    $plan->discounted_price = $price - $discount_value;

                    if($usedCoupun >= $coupons->limit)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price     = $price - $discount_value;
                    $coupon_id = $coupons->id;
                }
                else
                {
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
                            'payment_type' => 'Easybuzz',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id);

                    return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                }
            }
            $easebuzz_merchant_key = isset($adminPaymentSettings['easebuzz_merchant_key']) ? $adminPaymentSettings['easebuzz_merchant_key'] : '';
            $easebuzz_salt_key = isset($adminPaymentSettings['easebuzz_salt_key']) ? $adminPaymentSettings['easebuzz_salt_key'] : '';
            $easebuzz_enviroment_name = isset($adminPaymentSettings['easebuzz_enviroment_name']) ? $adminPaymentSettings['easebuzz_enviroment_name'] : '';
            $call_back = route('plan.get.easebuzz.notify', ['plan_id' => $plan->id, 'coupon_code' => $request->coupon, 'amount' => $price]) . '?_token=' . csrf_token();
            $returnURL = route('plan.easebuzz.return') . '?_token=' . csrf_token();
            $transaction_id = strtoupper(str_replace('.', '', uniqid('', true)));
            $easebuzzObj = new Easebuzz($easebuzz_merchant_key, $easebuzz_salt_key, $easebuzz_enviroment_name);

            $price = number_format((float)$price, 2, '.', '');
            $postData = array(
                "txnid" => $transaction_id,
                "amount" => $price,
                "firstname" => $authuser->name,
                "email" => $authuser->email,
                "phone" => '1111111111',
                "productinfo" => $plan->name,
                "surl" => $call_back,
                "furl" => $returnURL,
                "udf1" => "aaaa",
                "udf2" => "aaaa",
                "udf3" => "aaaa",
                "udf4" => "aaaa",
                "udf5" => "aaaa",
                "address1" => "aaaa",
                "address2" => "aaaa",
                "city" => "aaaa",
                "state" => "aaaa",
                "country" => "aaaa",
                "zipcode" => "123123"
            );
            $easebuzzObj->initiatePaymentAPI($postData);
            if ($easebuzzObj) {
               
                return redirect()->route('plans.index')->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->route('plans.index')->with('error', 'Plan is deleted.');
        }
    }
    public function notify_url(Request $request)
    {
       
        $plan  = Plan::find($request->plan_id);
        $user = \Auth::user();
        $orderID = time();
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
        if ($plan) {
            $price                  = $request->amount;
            if ($plan && $request->error == 'Transaction is successful.') {               
                $order = new PlanOrder();
                $order->order_id = $orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $price;
                $order->price_currency = $currency;
                $order->txn_id = time();
                $order->payment_type = __('Easebuzz');
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
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
            }
        }
    }
    public function return_url(Request $request)
    {
        return redirect()->route('plans.index')->with('error', __('Transaction has been failed'));
    }
}
