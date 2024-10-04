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

class CinetPayController extends Controller
{
    public function planPayWithCinetPay(Request $request)
    {
        $planID = Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $api_key = $adminPaymentSettings['cinetpay_api_key'];
        $site_id = $adminPaymentSettings['cinetpay_site_id'];
        $currency = !empty($adminPaymentSettings['CURRENCY']) ? $adminPaymentSettings['CURRENCY'] : 'USD';
        $plan = Plan::find($planID);
        $coupon_id = '0';
        $price = $plan->price;
        $coupon_code = null;
        $discount_value = null;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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

        $cinetpay_data = [
            "amount" => $price,
            "currency" => $currency,
            "apikey" => $api_key,
            "site_id" => $site_id,
            "transaction_id" => $orderID,
            "description" => $plan->name,
            "return_url" => route('plan.get.cinetpay.success'). '?_token=' . csrf_token(),
            "metadata" => "user001",
            'customer_surname' => $authuser->name,
            'customer_name' => $authuser->name,
            'customer_email' => $authuser->email,
            'customer_phone_number' => '9658745214',
            'customer_address' => 'abu dhabi',
            'customer_city' => 'texas',
            'customer_country' => 'BF',
            'customer_state' => 'USA',
            'customer_zip_code' => '123456',
            'channel' => 'ALL',
            "invoice_data" => [
                "get_amount" => $price,
                "coupon_id" => $coupon_id,
                "plan" => $plan->id,
            ]
        ];
        $csrfToken = csrf_token();
        $requestHeaders = [
            "Content-Type: application/json",
            "X-CSRF-TOKEN: $csrfToken",
        ];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 45,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($cinetpay_data),
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => $requestHeaders, // Include request headers
            )
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        //On recupère la réponse de CinetPay
        $response_body = json_decode($response, true);
        if (isset($response_body) && $response_body['code'] == '201') {
            $payment_link = $response_body["data"]["payment_url"]; // Retrieving the payment URL
            //Recording information in the database
            //Then redirect to the payment page
            return redirect($payment_link);
        } else {
            return redirect()
                ->route('plans.index', \Illuminate\Support\Facades\Crypt::encrypt($plan->id))
                ->with('error', $response->message ?? 'Something went wrong.');
        }
    }
  
    public function planGetCinetPayStatus(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $api_key = $payment_setting['cinetpay_api_key'];
        $site_id = $payment_setting['cinetpay_site_id'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));


        $user = \Auth::user();

        $cinetpay_check = [
            "apikey" => $api_key,
            "site_id" => $request->cpm_site_id,
            "transaction_id" => $request->cpm_trans_id
        ];

        $response = $this->getPayStatus($cinetpay_check); // call query function to retrieve status
        
        //We get the response from CinetPay
        $response_body = json_decode($response, true);

        if ($response_body['code'] == '00') {
            $data = $response_body['data'];
            $plan = Plan::find($data['plan']);

            $order = new PlanOrder();
            $order->order_id = time();
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $plan->name;
            $order->plan_id = $plan->id;
            $order->price = $data['get_amount'];
            $order->price_currency = $currency;
            $order->txn_id = time();
            $order->payment_type = __('Cinetpay');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '';
            $order->user_id = $user->id;
            $order->save();
            $user = User::find($user->id);
            $coupons = Coupon::where('id', $data['coupon_id'])->where('is_active', '1')->first();
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
    public function getPayStatus($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment/check',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type:application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err)
         print ($err);
        else
        return ($response);
    }
}
