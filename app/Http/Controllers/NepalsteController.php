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

class NepalsteController extends Controller
{
    public function planPayWithNepalste(Request $request)
    {

        $planID = Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $public_key = $adminPaymentSettings['nepalste_public_key'];
        $mode = $adminPaymentSettings['nepalste_mode'];
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
                            'payment_type' => 'Neplaste',
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
            if (!empty($request->coupon)) {
                $response = ['get_amount' => $price, 'plan' => $plan, 'coupon_id' => $coupons->id];
            } else {
                $response = ['get_amount' => $price, 'plan' => $plan];
            }
            $parameters = [
                'identifier' => 'DFU80XZIKS',
                'currency' => $currency,
                'amount' => $price,
                'details' => $plan->name,
                'ipn_url' => route('plan.get.nepalste.status',$response),
                'cancel_url' => route('plan.get.nepalste.cancel'),
                'success_url' => route('plan.get.nepalste.status',$response),
                'public_key' => $public_key,
                'site_logo' => 'https://nepalste.com.np/assets/images/logoIcon/logo.png',
                'checkout_theme' => 'dark',
                'customer_name' => 'John Doe',
                'customer_email' => 'john@mail.com',
            ];

           

            //test end point
            $url = "https://nepalste.com.np/".$mode."/payment/initiate";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $parameters);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
    
            $result = json_decode($result, true);
    
            if(isset($result['success'])){
                return redirect($result['url']);
            }else{
                return redirect()->back()->with('error',__($result['message']));
            }
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('errors', $e->getMessage());
        }

    }

    public function planGetNepalsteStatus(Request $request, $planID, $price, $coupanCode = null)
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
        $order->payment_type = __('Neplaste');
        $order->payment_status = 'success';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();
        $user = User::find($user->id);
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

    public function planGetNepalsteCancel(Request $request)
    {
        return redirect()->back()->with('error',__('Transaction has been failed'));
    }
}
