<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\PlanOrder;
use App\Models\Plan;
use App\Transaction;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\Business;
use App\Models\CardPayment;
use Illuminate\Http\RedirectResponse;
use Nwidart\Modules\Facades\Module;


class StripePaymentController extends Controller
{
    public $settings;
    public function index()
    {
        $objUser = \Auth::user();
        $orders = PlanOrder::select(
            [
                'plan_orders.*',
                'users.name as user_name',
            ]
        )->with('total_coupon_used.coupon_detail')
            ->join('users', 'plan_orders.user_id', '=', 'users.id')
            ->orderBy('plan_orders.created_at', 'DESC')
            ->get();


        $ordersDetails = PlanOrder::select('*')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('plan_orders')
                    ->groupBy('user_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.index', compact('orders', 'ordersDetails'));
    }


    public function stripe($code)
    {
        
        try {
            $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
            $plan = Plan::find($plan_id);
            $admin_payment_setting = Utility::getAdminPaymentSetting();
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', __('Plan not avaliable'));
        }
        $modules=[];
        $modules = Module::getByStatus(1);
        if ($plan) {
            return view('stripe', compact('plan', 'admin_payment_setting','modules'));
        } else {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }

    public function stripePost(Request $request)
    {
        

        $objUser = \Auth::user();
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);

        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $admin_currency=!empty($admin_payment_setting['CURRENCY']) ? $admin_payment_setting['CURRENCY'] : 'USD';

        if ($plan) {

            try {
                $price = $plan->price;

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $price = $plan->price - $discount_value;


                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $product = $plan->name;
                $code = '';
                /* Final price */
                $stripe_formatted_price = in_array(
                    $admin_currency,
                    [
                        'MGA',
                        'BIF',
                        'CLP',
                        'PYG',
                        'DJF',
                        'RWF',
                        'GNF',
                        'UGX',
                        'JPY',
                        'VND',
                        'VUV',
                        'XAF',
                        'KMF',
                        'KRW',
                        'XOF',
                        'XPF',
                    ]
                ) ? number_format($price, 2, '.', '') : number_format($price, 2, '.', '') * 100;
                $return_url_parameters = function ($return_type) {
                    return '&return_type=' . $return_type . '&payment_processor=stripe';
                };
                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey($admin_payment_setting['stripe_secret']);

                $stripe_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => $admin_currency,
                                'product_data' => [
                                    'name' => $product,
                                    'description' => $product,
                                ],
                                'unit_amount' => $stripe_formatted_price,
                            ],
                            'quantity' => 1,
                        ],
                    ],
                    'metadata' => [
                        'user_id' => $objUser->id,
                        'package_id' => $plan->id,
                        'code' => $code,
                    ],
                    'mode' => 'payment',
                    'success_url' => route('stripe.payment.status', [
                        'plan_id' => $plan->id,
                        'currency' => $admin_currency,
                        'amount' => $price,
                        'coupon_id' => $request->coupon,
                        $return_url_parameters('success'),
                    ]),
                    'cancel_url' => route('stripe.payment.status', [
                        'plan_id' => $plan->id,
                        'currency' => $admin_currency,
                        'amount' => $price,
                        'coupon_id' => $request->coupon,
                        $return_url_parameters('cancel'),
                    ]),
                ]);
                

                $stripe_session = $stripe_session ?? false;

                try {
                    return new RedirectResponse($stripe_session->url);
                } catch (\Exception $e) {
                    return redirect()->route('plans.index')->with('error', __('Transaction has been failed!'));
                }
          

                if ($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1) {

                    PlanOrder::create([
                        'order_id' => $orderID,                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
                        'name' => $request->name,
                        'card_number' => isset($data['payment_method_details']['card']['last4']) ? $data['payment_method_details']['card']['last4'] : '',
                        'card_exp_month' => isset($data['payment_method_details']['card']['exp_month']) ? $data['payment_method_details']['card']['exp_month'] : '',
                        'card_exp_year' => isset($data['payment_method_details']['card']['exp_year']) ? $data['payment_method_details']['card']['exp_year'] : '',
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'price' => $price,
                        'price_currency' => !empty($admin_payment_setting['CURRENCY']) ? $admin_payment_setting['CURRENCY'] : '',
                        'txn_id' => '',
                        'payment_type' => __('STRIPE'),
                        'payment_status' => isset($data['status']) ? $data['status'] : 'success',
                        'receipt' => isset($data['receipt_url']) ? $data['receipt_url'] : 'free coupon',
                        'user_id' => $objUser->id,
                    ]);

                    if (!empty($request->coupon)) {

                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $objUser->id;
                        $userCoupon->coupon = $coupons->id;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
                        $userCoupon->order = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                        

                    }                                                                                                                       

                    if ($data['status'] == 'succeeded') {
                        $assignPlan = $objUser->assignPlan($plan->id);
                        if ($assignPlan['is_success']) {
                            return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
                        } else {
                            return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                        }
                    } else {
                        return redirect()->route('plans.index')->with('error', __('Your payment has failed.'));
                    }
                } else {
                    return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
                }
            } catch (\Exception $e) {


                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetStripePaymentStatus(Request $request)
    {
        
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        $plan = Plan::find($request->plan_id);

        Session::forget('stripe_session');

        try {
            if ($request->return_type == 'success') {
                $objUser                    = \Auth::user();

                $assignPlan = $objUser->assignPlan($request->plan_id);
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                if ($request->has('coupon_id') && $request->coupon_id != '') {
                   
                    if (!empty($request->coupon_id)) {
                        $coupons = Coupon::where('code', strtoupper($request->coupon_id))->where('is_active', '1')->first();
                        
                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $objUser->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();

                        $usedCoupun = $coupons->used_coupon();
                        
                    }
                }

                PlanOrder::create(
                    [
                        'order_id' => $orderID,
                        'name' => $objUser->name,
                        'card_number' => '',
                        'card_exp_month' => '',
                        'card_exp_year' => '',
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'price' =>  $request->amount,
                        'price_currency' => !empty($admin_payment_setting['CURRENCY']) ? $admin_payment_setting['CURRENCY'] : 'USD',
                        'txn_id' => '',
                        'payment_type' => 'STRIPE',
                        'payment_status' => $request->return_type,
                        'receipt' => '',
                        'user_id' => $objUser->id,
                    ]
                );
                Utility::referralTransaction($plan);
                if ($assignPlan['is_success']) {

                    return redirect()->route('plans.index')->with('success', __('Plan successfully activated.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
            }
        } catch (\Exception $exception) {
            return redirect()->route('plans.index')->with('error', __('Something went wrong.'));
        }
    }

    public function cardPayWithStripe(Request $request, $id)
    {
        $paymentData = CardPayment::cardPaymentData($id);
        $business = Business::find($id);
        $amount = $paymentData->payment_amount;
        $content = json_decode($paymentData->content);
        $stripe_key = $content->stripe->stripe_key;
        $stripe_secret = $content->stripe->stripe_secret;
        $currancy = 'INR';

        /* Final price */
        $stripe_formatted_price = in_array(
            $currancy,
            [
                'MGA',
                'BIF',
                'CLP',
                'PYG',
                'DJF',
                'RWF',
                'GNF',
                'UGX',
                'JPY',
                'VND',
                'VUV',
                'XAF',
                'KMF',
                'KRW',
                'XOF',
                'XPF',
            ]
        ) ? number_format($amount, 2, '.', '') : number_format($amount, 2, '.', '') * 100;

        $return_url_parameters = function ($return_type) {
            return '&return_type=' . $return_type . '&payment_processor=stripe';
        };

        /* Initiate Stripe */
        \Stripe\Stripe::setApiKey($stripe_secret);
        $code = '';
        $product = $business->title;
        $stripe = new \Stripe\StripeClient($stripe_secret);
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => $product,
                        ],
                        'unit_amount' => $stripe_formatted_price,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route(
                'card.stripe',
                [
                    'cardpayment_id' => $paymentData->id,
                    'business_id' => $business->id,
                    $return_url_parameters('success'),
                ]
            ),
            'cancel_url' => route(
                'card.stripe',
                [
                    'cardpayment_id' => $paymentData->id,
                    'business_id' => $business->id,
                    $return_url_parameters('cancel'),
                ]
            ),


        ]);

        $checkout_session = $checkout_session ?? false;

        try {
            return new RedirectResponse($checkout_session->url);
        } catch (\Exception $e) {
            return redirect(url('/' . $business->slug))->with('error', __('Transaction has been failed!'));
        }
    }

    public function cardGetStripePaymentStatus(Request $request)
    {
        $cardPayment = CardPayment::cardPaymentData($request->business_id);
        $business = Business::find($request->business_id);
        $content = json_decode($cardPayment->content);
        if ($request->return_type == 'success') {
            $cardPayment->payment_status = 'Paid';
            $cardPayment->payment_type = __('Stripe');
            $cardPayment->save();
            return redirect(url('/' . $business->slug))->with('success', 'Payment Added Succesfully');
        } else {
            return redirect(url('/' . $business->slug))->with('error', 'Something went wrong.');
        }
    }

}
