<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\PlanOrder;
use App\Models\Plan;
use App\Models\Utility;
use App\Models\UserCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use PaytmWallet;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;


class PaytmPaymentController extends Controller
{
    public function paymentConfig()
    {

        $payment_setting = Utility::getAdminPaymentSetting();
        config(
            [
                'services.paytm-wallet.env' => isset($payment_setting['paytm_mode']) ? $payment_setting['paytm_mode'] : '',
                'services.paytm-wallet.merchant_id' => isset($payment_setting['paytm_merchant_id']) ? $payment_setting['paytm_merchant_id'] : '',
                'services.paytm-wallet.merchant_key' => isset($payment_setting['paytm_merchant_key']) ? $payment_setting['paytm_merchant_key'] : '',
                'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                'services.paytm-wallet.channel' => 'WEB',
                'services.paytm-wallet.industry_type' => isset($payment_setting['paytm_industry_type']) ? $payment_setting['paytm_industry_type'] : '',
                ]
            );
    }
    public function planPayWithPaytm(Request $request)
    {
        $payment    = $this->paymentConfig();
        $payment_setting = Utility::getAdminPaymentSetting();
        $planID     = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan       = Plan::find($planID);
        $authuser   = \Auth::user();
        $coupons_id = '';
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
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id             = $coupons->id;
                    if($usedCoupun >= $coupons->limit)
                    {
                        return Utility::error_res(__('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return Utility::error_res(__('This coupon code is invalid or has expired.'));
                }
            }
            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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
                            'price_currency' => !empty($payment_setting['CURRENCY']) ? $payment_setting['CURRENCY'] : 'USD',
                            'txn_id' => '',
                            'payment_type' => 'Paytm',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return Utility::error_res(__('Plan fail to upgrade.'));
                }
            }
            $call_back = route(
                'plan.paytm', [
                                $request->plan_id,
                                'coupon_id=' . $coupons_id,
                            ]
            );
            $payment   = PaytmWallet::with('receive');
            
            $payment->prepare(
                [
                    'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                    'user' => Auth::user()->id,
                    'mobile_number' => $request->mobile,
                    'email' => Auth::user()->email,
                    'amount' => $price,
                    'plan' => $plan->id,
                    'callback_url' => $call_back,
                ]
            );
            return $payment->receive();
            
        }
        else
        {
            return Utility::error_res(__('Plan is deleted.'));
        }

    }

    public function getPaymentStatus(Request $request, $plan)
    {
        
        $transaction    = $this->paymentConfig();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan    = Plan::find($planID);
        $payment_setting = Utility::getAdminPaymentSetting();
        $user   = \Auth::user();
        $orderID = time();
        if($plan)
        {
                $transaction = PaytmWallet::with('receive');
                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $orderID;
                            $userCoupon->save();


                            $usedCoupun = $coupons->used_coupon();
                           
                        }
                    }

                    $order                 = new PlanOrder();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($request->TXNAMOUNT) ? $request->TXNAMOUNT : 0;
                    $order->price_currency = !empty($payment_setting['CURRENCY']) ? $payment_setting['CURRENCY'] : 'USD';
                    $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                    $order->payment_type   = __('paytm');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    Utility::referralTransaction($plan);
                    $assignPlan = $user->assignPlan($plan->id);

                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    }
                    else
                    {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }


        }
    }

}
