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

class PaiementProController extends Controller
{
    public function planPayWithpaiementpro(Request $request)
    {
        $planID = Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $merchant_id = $adminPaymentSettings['paiement_merchant_id'];
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
                            'payment_type' => 'Paiement Pro',
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

        if (!empty($request->coupon)) {
            $response = ['plan' => $plan,];
        } else {
            $response = ['plan' => $plan];
        }
        $data = array(
            'merchantId' => $merchant_id,
            'amount' => $price,
            'description' => "Api PHP",
            'channel' => $request->channel,
            'countryCurrencyCode' => $currency,
            'referenceNumber' => "REF-" . time(),
            'customerEmail' => $authuser->email,
            'customerFirstName' => $authuser->name,
            'customerLastname' => $authuser->name,
            'customerPhoneNumber' => $request->mobile,
            'notificationURL' => route('plan.get.paiementpro.status', $response),
            'returnURL' => route('plan.get.paiementpro.status', $response),
            'returnContext' => json_encode([
                "get_amount" => $price,
                "coupon_id" => $coupon_id,
            ])


        );

        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paiementpro.net/webservice/onlinepayment/init/curl-init.php");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);


        curl_close($ch);
        $response = json_decode($response);
        if (isset($response->success) && $response->success == true) {
            // redirect to approve href
            return redirect($response->url);
        } else {
            return redirect()
                ->route('plans.index', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                ->with('error', $response->message ?? 'Something went wrong.');
        }
    }
    public function planGetpaiementproStatus(Request $request, $planID)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $jsonData = $request->returnContext;
        $dataArray = json_decode($jsonData, true);

        $getAmount = $request->get_amount;
        if ($request->responsecode == 0) {
            $user = \Auth::user();
            $plan = Plan::find($request->plan);

            $order = new PlanOrder();
            $order->order_id = time();
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $plan->name;
            $order->plan_id = $plan->id;
            $order->price = $dataArray['get_amount'];
            $order->price_currency = $currency;
            $order->txn_id = time();
            $order->payment_type = __('Paiement Pro');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '';
            $order->user_id = $user->id;
            $order->save();
            $user = User::find($user->id);
            $coupons = Coupon::where('id', $dataArray['coupon_id'])->where('is_active', '1')->first();
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
            return redirect()->back()->with('error', __('Transaction has been failed'));

        }
    }
}
