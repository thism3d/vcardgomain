@php
    $user = Auth::user();
    $payment_setting = \App\Models\Utility::getAdminPaymentSetting();
@endphp
<div>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if (isset($payment_setting['is_manually_enabled']) && $payment_setting['is_manually_enabled'] == 'on')
            <li class="nav-item">
                <a class="nav-link active" id="pills-manually-tab" data-bs-toggle="pill" href="#manual_payment"
                    role="tab" aria-controls="pills-manually" aria-selected="false">{{ __('Manually') }}</a>
            </li>
        @endif
        @if (
            $payment_setting['is_stripe_enabled'] == 'on' &&
                !empty($payment_setting['stripe_key']) &&
                !empty($payment_setting['stripe_secret']))
            <li class="nav-item">
                <a class="nav-link" id="pills-home-tab" data-bs-toggle="pill" href="#stripe-payment" role="tab"
                    aria-controls="pills-home" aria-selected="false">{{ __('Stripe') }}</a>
            </li>
        @endif
        @if (isset($payment_setting['is_bank_enabled']) && $payment_setting['is_bank_enabled'] == 'on')
            @if (isset($payment_setting['bank_detail']) && !empty($payment_setting['bank_detail']))
                <li class="nav-item">
                    <a href="#bank-transfer-payment" class="nav-link" id="pills-banktransfer-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="banktransfer" aria-selected="false">{{ __('Bank Transfer') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_paypal_enabled']) && $payment_setting['is_paypal_enabled'] == 'on')
            @if (isset($payment_setting['paypal_client_id']) &&
                    !empty($payment_setting['paypal_client_id']) &&
                    (isset($payment_setting['paypal_secret_key']) && !empty($payment_setting['paypal_secret_key'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-paypal-tab" data-bs-toggle="pill" href="#paypal-payment"
                        role="tab" aria-controls="paypal" aria-selected="false">{{ __('Paypal') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_paystack_enabled']) && $payment_setting['is_paystack_enabled'] == 'on')
            @if (isset($payment_setting['paystack_public_key']) &&
                    !empty($payment_setting['paystack_public_key']) &&
                    (isset($payment_setting['paystack_secret_key']) && !empty($payment_setting['paystack_secret_key'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-paystack-tab" data-bs-toggle="pill" href="#paystack-payment"
                        role="tab" aria-controls="paystack" aria-selected="false">{{ __('Paystack') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_flutterwave_enabled']) && $payment_setting['is_flutterwave_enabled'] == 'on')
            @if (isset($payment_setting['flutterwave_secret_key']) &&
                    !empty($payment_setting['flutterwave_secret_key']) &&
                    (isset($payment_setting['flutterwave_public_key']) && !empty($payment_setting['flutterwave_public_key'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-flutterwave-tab" data-bs-toggle="pill" href="#flutterwave-payment"
                        role="tab" aria-controls="flutterwave" aria-selected="false">{{ __('Flutterwave') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_razorpay_enabled']) && $payment_setting['is_razorpay_enabled'] == 'on')
            @if (isset($payment_setting['razorpay_public_key']) &&
                    !empty($payment_setting['razorpay_public_key']) &&
                    (isset($payment_setting['razorpay_secret_key']) && !empty($payment_setting['razorpay_secret_key'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-razorpay-tab" data-bs-toggle="pill" href="#razorpay-payment"
                        role="tab" aria-controls="razorpay" aria-selected="false">{{ __('Razorpay') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_mercado_enabled']) && $payment_setting['is_mercado_enabled'] == 'on')
            @if (isset($payment_setting['mercado_access_token']) && !empty($payment_setting['mercado_access_token']))
                <li class="nav-item">
                    <a class="nav-link" id="pills-mercado-tab" data-bs-toggle="pill" href="#mercado-payment"
                        role="tab" aria-controls="mercado" aria-selected="false">{{ __('Mercado Pago') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_paytm_enabled']) && $payment_setting['is_paytm_enabled'] == 'on')
            @if (isset($payment_setting['paytm_merchant_id']) &&
                    !empty($payment_setting['paytm_merchant_id']) &&
                    (isset($payment_setting['paytm_merchant_key']) && !empty($payment_setting['paytm_merchant_key'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-paytm-tab" data-bs-toggle="pill" href="#paytm-payment" role="tab"
                        aria-controls="paytm" aria-selected="false">{{ __('Paytm') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_mollie_enabled']) && $payment_setting['is_mollie_enabled'] == 'on')
            @if (isset($payment_setting['mollie_api_key']) &&
                    !empty($payment_setting['mollie_api_key']) &&
                    (isset($payment_setting['mollie_profile_id']) && !empty($payment_setting['mollie_profile_id'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-mollie-tab" data-bs-toggle="pill" href="#mollie-payment"
                        role="tab" aria-controls="mollie" aria-selected="false">{{ __('Mollie') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_skrill_enabled']) && $payment_setting['is_skrill_enabled'] == 'on')
            @if (isset($payment_setting['skrill_email']) && !empty($payment_setting['skrill_email']))
                <li class="nav-item">
                    <a class="nav-link" id="pills-skrill-tab" data-bs-toggle="pill" href="#skrill-payment"
                        role="tab" aria-controls="skrill" aria-selected="false">{{ __('Skrill') }}</a>
                </li>
            @endif
        @endif
        @if (isset($payment_setting['is_coingate_enabled']) && $payment_setting['is_coingate_enabled'] == 'on')
            @if (isset($payment_setting['coingate_auth_token']) && !empty($payment_setting['coingate_auth_token']))
                <li class="nav-item">
                    <a class="nav-link" id="pills-coingate-tab" data-bs-toggle="pill" href="#coingate-payment"
                        role="tab" aria-controls="coingate" aria-selected="false">{{ __('CoinGate') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_paymentwall_enabled']) && $payment_setting['is_paymentwall_enabled'] == 'on')
            @if (isset($payment_setting['paymentwall_public_key']) &&
                    !empty($payment_setting['paymentwall_public_key']) &&
                    (isset($payment_setting['paymentwall_private_key']) && !empty($payment_setting['paymentwall_private_key'])))
                <li class="nav-item">
                    <a class="nav-link" id="pills-paymentwall-tab" data-bs-toggle="pill" href="#paymentwall-payment"
                        role="tab" aria-controls="paymentwall" aria-selected="false">{{ __('PaymentWall') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_toyyibpay_enabled']) && $payment_setting['is_toyyibpay_enabled'] == 'on')
            @if (isset($payment_setting['toyyibpay_secret_key']) &&
                    !empty($payment_setting['toyyibpay_secret_key']) &&
                    (isset($payment_setting['toyyibpay_secret_key']) && !empty($payment_setting['toyyibpay_secret_key'])))
                <li class="nav-item">
                    <a href="#toyyibpay-payment" class="nav-link" id="pills-toyyibpay-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="toyyibpay" aria-selected="false">{{ __('Toyyibpay') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_payfast_enabled']) && $payment_setting['is_payfast_enabled'] == 'on')
            @if (isset($payment_setting['payfast_merchant_id']) &&
                    !empty($payment_setting['payfast_merchant_id']) &&
                    (isset($payment_setting['payfast_merchant_key']) && !empty($payment_setting['payfast_merchant_key'])))
                <li class="nav-item">
                    <a href="#payfast-payment" class="nav-link" id="pills-payfast-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="payfast" aria-selected="false">{{ __('Payfast') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_sspay_enabled']) && $payment_setting['is_sspay_enabled'] == 'on')
            @if (isset($payment_setting['sspay_category_code']) &&
                    !empty($payment_setting['sspay_category_code']) &&
                    (isset($payment_setting['sspay_secret_key']) && !empty($payment_setting['sspay_secret_key'])))
                <li class="nav-item">
                    <a href="#sspay-payment" class="nav-link" id="pills-sspay-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="sspay" aria-selected="false">{{ __('SSPay') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_iyzipay_enabled']) && $payment_setting['is_iyzipay_enabled'] == 'on')
            @if (isset($payment_setting['iyzipay_key']) &&
                    !empty($payment_setting['iyzipay_key']) &&
                    (isset($payment_setting['iyzipay_secret']) && !empty($payment_setting['iyzipay_secret'])))
                <li class="nav-item">
                    <a href="#iyzipay-payment" class="nav-link" id="pills-iyzipay-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="iyzipay" aria-selected="false">{{ __('IyziPay') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_paytab_enabled']) && $payment_setting['is_paytab_enabled'] == 'on')
            @if (isset($payment_setting['paytab_profile_id']) &&
                    !empty($payment_setting['paytab_profile_id']) &&
                    (isset($payment_setting['paytab_region']) && !empty($payment_setting['paytab_region'])))
                <li class="nav-item">
                    <a href="#paytab-payment" class="nav-link" id="pills-paytab-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="paytab" aria-selected="false">{{ __('Paytab') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_benefit_enabled']) && $payment_setting['is_benefit_enabled'] == 'on')
            @if (isset($payment_setting['benefit_secret_key']) &&
                    !empty($payment_setting['benefit_secret_key']) &&
                    (isset($payment_setting['benefit_publishable_key']) && !empty($payment_setting['benefit_publishable_key'])))
                <li class="nav-item">
                    <a href="#benefit-payment" class="nav-link" id="pills-benefit-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="benefit" aria-selected="false">{{ __('Benefit') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_cashfree_enabled']) && $payment_setting['is_cashfree_enabled'] == 'on')
            @if (isset($payment_setting['cashfree_key']) &&
                    !empty($payment_setting['cashfree_key']) &&
                    (isset($payment_setting['cashfree_secret']) && !empty($payment_setting['cashfree_secret'])))
                <li class="nav-item">
                    <a href="#cashfree-payment" class="nav-link" id="pills-cashfree-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="cashfree" aria-selected="false">{{ __('CashFree') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_aamarpay_enabled']) && $payment_setting['is_aamarpay_enabled'] == 'on')
            @if (isset($payment_setting['aamarpay_store_id']) &&
                    !empty($payment_setting['aamarpay_store_id']) &&
                    (isset($payment_setting['aamarpay_signature_key']) && !empty($payment_setting['aamarpay_signature_key'])))
                <li class="nav-item">
                    <a href="#aamarpay-payment" class="nav-link" id="pills-aamarpay-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="aamarpay" aria-selected="false">{{ __('AamarPay') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_paytr_enabled']) && $payment_setting['is_paytr_enabled'] == 'on')
            @if (isset($payment_setting['paytr_merchant_id']) &&
                    !empty($payment_setting['paytr_merchant_id']) &&
                    (isset($payment_setting['paytr_merchant_key']) && !empty($payment_setting['paytr_merchant_key'])))
                <li class="nav-item">
                    <a href="#paytr-payment" class="nav-link" id="pills-paytr-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="paytr" aria-selected="false">{{ __('PayTR') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_midtrans_enabled']) && $payment_setting['is_midtrans_enabled'] == 'on')
            @if (isset($payment_setting['midtrans_secret_key']) && !empty($payment_setting['midtrans_secret_key']))
                <li class="nav-item">
                    <a href="#midtrans-payment" class="nav-link" id="pills-midtrans-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="midtrans" aria-selected="false">{{ __('Midtrans') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_xendit_enabled']) && $payment_setting['is_xendit_enabled'] == 'on')
            @if (isset($payment_setting['xendit_api_key']) && !empty($payment_setting['xendit_api_key']))
                <li class="nav-item">
                    <a href="#xendit-payment" class="nav-link" id="pills-xendit-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="xendit" aria-selected="false">{{ __('Xendit') }}</a>
                </li>
            @endif
        @endif

        @if (isset($payment_setting['is_yookassa_enabled']) && $payment_setting['is_yookassa_enabled'] == 'on')
            @if (isset($payment_setting['yookassa_shop_id']) && !empty($payment_setting['yookassa_secret_key']))
                <li class="nav-item">
                    <a href="#yookassa-payment" class="nav-link" id="pills-yookassa-tab" data-bs-toggle="pill"
                        role="tab" aria-controls="yookassa" aria-selected="false">{{ __('YooKassa') }}</a>
                </li>
            @endif
        @endif
    </ul>
</div>

<div class="tab-content">
                {{-- stripe payment --}}
                @if (
                    $payment_setting['is_stripe_enabled'] == 'on' &&
                        !empty($payment_setting['stripe_key']) &&
                        !empty($payment_setting['stripe_secret']))
                        <div class="tab-pane fade border p-3" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                        <form role="form" class="require-validation" id="payment-form">
                            @csrf
                            <div class="card-header">
                                <h5>{{ __('Stripe') }}</h5>
                            </div>
                            <div class="card-body">
                                <div
                                    class="tab-pane {{ ($payment_setting['is_stripe_enabled'] == 'on' && !empty($payment_setting['stripe_key']) && !empty($payment_setting['stripe_secret'])) == 'on' ? 'active' : '' }}">
    
                                    <div class="border p-3 stripe-payment-div">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-radio">
                                                    <label
                                                        class="font-16 font-weight-bold">{{ __('Credit / Debit Card') }}</label>
                                                </div>
                                                <p class="mb-0 pt-1 text-sm">
                                                    {{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.') }}
                                                </p>
                                            </div>
                                            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                <img src="{{ asset('public/custom/img/payments/master.png') }}"
                                                    height="24" alt="master-card-img">
                                                <img src="{{ asset('public/custom/img/payments/discover.png') }}"
                                                    height="24" alt="discover-card-img">
                                                <img src="{{ asset('public/custom/img/payments/visa.png') }}" height="24"
                                                    alt="visa-card-img">
                                                <img src="{{ asset('public/custom/img/payments/american express.png') }}"
                                                    height="24" alt="american-express-card-img">
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="card-name-on"
                                                        class="form-label text-dark">{{ __('Name on card') }}</label>
                                                    <input type="text" name="name" id="card-name-on"
                                                        class="form-control required"
                                                        placeholder="{{ \Auth::user()->name }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="card-element">
                                                    <!-- A Stripe Element will be inserted here. -->
                                                </div>
                                                @if ($payment_setting['CURRENCY'] != 'INR')
                                                    <div class="row mt-1">
                                                        <div class="col-md-4 form-group">
                                                            <label for="">{{ __('Country') }}</label>
                                                            <input type="text" name="country-dd" id="country"
                                                                placeholder="Enter Your Country Code" class="form-control"
                                                                required>
                                                            <small
                                                                class="text-muted">{{ __("Example:The country is 'United States' then enter the 'US' ") }}</small>
                                                            {{-- <input type="hidden" name="country-dd" id="country" placeholder="Enter Your Country" class="form-control" value="US" readonly> --}}
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label for="">{{ __('State') }}</label>
                                                            <input type="text" name="state-dd" id="state"
                                                                placeholder="Enter Your State" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-4 form-group">
                                                            <label for="">{{ __('City') }}</label>
                                                            <input type="text" name="city-dd" id="city"
                                                                placeholder="Enter Your City" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="">{{ __('Enter Address') }}</label>
                                                            <textarea name="address" id="address" cols="30" rows="3" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">{{ __('Enter Postal Code') }}</label>
                                                        <input type="text" name="postal_code" id="postal_code"
                                                            placeholder="Enter Postal Code" class="form-control" required>
                                                    </div>
                                                @endif
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                            <div class="col-md-11 mt-4">
                                                <div class="form-group">
                                                    <input type="text" id="stripe_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
    
                                            <div class="col-auto my-auto">
                                                <a href="#" class="text-white btn btn-lg btn-primary apply-coupon"
                                                    data-bs-toggle="tooltip" data-bs-title="{{ __('Apply') }}"><i
                                                        data-feather="save" class=""></i></a>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <b>{{ 'Net Amount : ' }}</b><span
                                                class="stripe_amount">{{ $payment_setting['CURRENCY_SYMBOL'] ? $payment_setting['CURRENCY_SYMBOL'] : '$' }}{{ $plan->price }}</span><br>
                                            <small>{{ __('(After coupon apply)') }}</small>
                                        </div>
    
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end Credit/Debit Card box-->
    
    
                                </div>
                            </div>
    
                            <div class="card-footer">
                                <div class="col-sm-12 my-2 px-2">
                                    <div class="text-end">
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                        <input type="submit" value="{{ __('Pay Now') }}"
                                            class="btn btn-lg btn-primary btn-create">
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- </div> --}}
                    </div>
                @endif
                {{-- stripr payment end --}}
    {{-- paypal end --}}
    @if (
        $payment_setting['is_paypal_enabled'] == 'on' &&
            !empty($payment_setting['paypal_client_id']) &&
            !empty($payment_setting['paypal_secret_key']))
        <div class="tab-pane fade border p-3" id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
            <div class="card-header">
                <h5>{{ __('Paypal') }}</h5>
            </div>

            <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                action="{{ route('addon.pay.with.paypal') }}">
                @csrf
                <div class="card-body">

                    <div class="tab-pane " id="paypal_payment">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="paypal_coupon" name="coupon"
                                        class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary btn-create apply-coupon"
                                    data-bs-toggle="tooltip" data-bs-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" value="{{ __('Buy Now') }}" class="btn btn-lg btn-primary btn-create">
                    </div>
                </div>
            </form>


        </div>
    @endif
    {{-- paypal end --}}
    {{-- Paytm --}}
    @if (isset($payment_setting['is_paytm_enabled']) && $payment_setting['is_paytm_enabled'] == 'on')
        <div class="tab-pane fade  " id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
            <div class="card-header">
                <h5>{{ __('Paytm') }}</h5>
            </div>

            <form role="form" action="{{ route('plan.pay.with.paytm') }}" method="post"
                class="require-validation" id="paytm-payment-form">
                @csrf
                <div class="card-body">

                    <div class="tab-pane " id="paytm_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="paytm_coupon" name="coupon"
                                        class="form-control coupon" data-from="paytm"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary  apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="flaterwave_coupon"
                                        class="form-label text-dark">{{ __('Mobile Number') }}</label>
                                    <input type="text" id="mobile" name="mobile" class="form-control mobile"
                                        data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_paytm" value="{{ __('Buy Now') }}"
                            class=" btn btn-lg btn-primary btn-create badge-blue">
                    </div>
                </div>
            </form>


        </div>
    @endif
    {{-- Paytm end --}}
    {{-- Flutterwave --}}
    @if (isset($payment_setting['is_flutterwave_enabled']) && $payment_setting['is_flutterwave_enabled'] == 'on')
        <div class="tab-pane fade border p-3 " id="flutterwave-payment" role="tabpanel"
            aria-labelledby="flutterwave-payment">
            <div class="card-header">
                <h5>{{ __('Flutterwave') }}</h5>

            </div>

            <form role="form" action="{{ route('plan.pay.with.flaterwave') }}" method="post"
                class="require-validation" id="flaterwave-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="flutterwave_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="flaterwave_coupon" name="coupon"
                                        class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="button" id="pay_with_flaterwave" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary">
                    </div>
                </div>
            </form>



        </div>
    @endif
    {{-- Flutterwave END --}}

    {{-- Razorpay --}}
    @if (isset($payment_setting['is_razorpay_enabled']) && $payment_setting['is_razorpay_enabled'] == 'on')
        <div class="tab-pane fade border p-3 " id="razorpay-payment" role="tabpanel"
            aria-labelledby="razorpay-payment">
            <div class="card-header">
                <h5>{{ __('Razorpay') }} </h5>

            </div>

            <form role="form" action="{{ route('plan.pay.with.razorpay') }}" method="post"
                class="require-validation" id="razorpay-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="razorpay_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="razorpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="razorpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary btn-create apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="button" id="pay_with_razorpay" value="{{ __('Buy Now') }}"
                            class="btn btn-lg btn-primary btn-create">
                    </div>
                </div>
            </form>

        </div>
    @endif
    {{-- Razorpay end --}}

    {{-- Mercado Pago --}}
    @if (isset($payment_setting['is_mercado_enabled']) && $payment_setting['is_mercado_enabled'] == 'on')
        <div class="tab-pane fade border p-3 " id="mercado-payment" role="tabpanel"
            aria-labelledby="mercado-payment">
            <div class="card-header">
                <h5>{{ __('Mercado Pago') }}</h5>

            </div>

            <form role="form" action="{{ route('plan.pay.with.mercado') }}" method="post"
                class="require-validation" id="mercado-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="mercado_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="mercado_coupon" name="coupon"
                                        class="form-control coupon" data-from="mercado"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_mercado" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>

        </div>
    @endif
    {{-- Mercado Pago end --}}
    {{-- Mollie --}}
    @if (isset($payment_setting['is_mollie_enabled']) && $payment_setting['is_mollie_enabled'] == 'on')
        <div class="tab-pane fade border p-3 " id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
            <div class="card-header">
                <h5>{{ __('Mollie') }}</h5>

            </div>

            <form role="form" action="{{ route('plan.pay.with.mollie') }}" method="post"
                class="require-validation" id="mollie-payment-form">
                @csrf
                <div class="card-body">

                    <div class="tab-pane " id="mollie_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="mollie_coupon" name="coupon"
                                        class="form-control coupon" data-from="mollie"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_mollie" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>

            </form>

            {{-- Mollie end --}}

        </div>
    @endif
    {{-- Skrill --}}
    @if (isset($payment_setting['is_skrill_enabled']) && $payment_setting['is_skrill_enabled'] == 'on')
        <div class="tab-pane fade border p-3 " id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
            <div class="card-header">
                <h5>{{ __('Skrill') }}</h5>

            </div>

            <form role="form" action="{{ route('plan.pay.with.skrill') }}" method="post"
                class="require-validation" id="skrill-payment-form">
                @csrf
                <div class="card-body">

                    <div class="tab-pane " id="skrill_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="skrill_coupon" name="coupon"
                                        class="form-control coupon" data-from="skrill"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>
                        @php
                            $skrill_data = [
                                'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                'user_id' => 'user_id',
                                'amount' => 'amount',
                                'currency' => 'currency',
                            ];
                            session()->put('skrill_data', $skrill_data);

                        @endphp

                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_skrill" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>


        </div>
    @endif
    {{-- Skrill end --}}

    {{-- Coingate --}}
    @if (isset($payment_setting['is_coingate_enabled']) && $payment_setting['is_coingate_enabled'] == 'on')
        <div class="tab-pane fade border p-3 " id="coingate-payment" role="tabpanel"
            aria-labelledby="coingate-payment">
            <div class="card-header">
                <h5>{{ __('Coingate') }}</h5>

            </div>

            <form role="form" action="{{ route('plan.pay.with.coingate') }}" method="post"
                class="require-validation" id="coingate-payment-form">
                @csrf
                <div class="card-body">

                    <div class="tab-pane " id="coingate_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="coingate_coupon" name="coupon"
                                        class="form-control coupon" data-from="coingate"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_coingate" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary">
                    </div>
                </div>
            </form>


        </div>
    @endif
    {{-- Coingate end --}}

    {{-- Paymentwall --}}
    @if (isset($payment_setting['is_paymentwall_enabled']) && $payment_setting['is_paymentwall_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="paymentwall-payment" role="tabpanel"
            aria-labelledby="paymentwall-payment-tab">
            <div class="card-header">
                <h5>{{ __('Paymentwall') }}</h5>
            </div>

            <form role="form" action="{{ route('paymentwall') }}" method="post" class="require-validation"
                id="coingate-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="paymentwall_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="paymentwall_coupon" name="coupon"
                                        class="form-control coupon" data-from="paymentwall"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_paymentwall" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Paymentwall end --}}

    {{-- Toyyibpay --}}
    @if (isset($payment_setting['is_toyyibpay_enabled']) && $payment_setting['is_toyyibpay_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="toyyibpay-payment" role="tabpanel"
            aria-labelledby="toyyibpay-payment-tab">
            <div class="card-header">
                <h5>{{ __('Toyyibpay') }}</h5>
            </div>

            <form role="form" action="{{ route('plan.pay.with.toyyibpay') }}" method="post"
                class="require-validation" id="coingate-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="toyyibpay_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="pay_with_toyyibpay" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Toyyibpay end --}}
    {{-- Payfast --}}
    @if (isset($payment_setting['is_payfast_enabled']) && $payment_setting['is_payfast_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="payfast-payment" role="tabpanel"
            aria-labelledby="payfast-payment-tab">
            <div class="card-header">
                <h5>{{ __('Payfast') }}</h5>
            </div>

            @if (
                $payment_setting['is_payfast_enabled'] == 'on' &&
                    !empty($payment_setting['payfast_merchant_id']) &&
                    !empty($payment_setting['payfast_merchant_key']) &&
                    !empty($payment_setting['payfast_signature']) &&
                    !empty($payment_setting['payfast_mode']))
                <div
                    class="tab-pane {{ ($payment_setting['is_payfast_enabled'] == 'on' && !empty($payment_setting['payfast_merchant_id']) && !empty($payment_setting['payfast_merchant_key'])) == 'on' ? 'active' : '' }}">
                    @php
                        $pfHost = $payment_setting['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                    @endphp
                    <form role="form" action={{ 'https://' . $pfHost . '/eng/process' }} method="post"
                        class="require-validation" id="payfast-form">
                        <div class="card-body">
                            <div class="tab-pane " id="toyyibpay_payment">
                                <input type="hidden" name="plan_id"
                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="d-flex align-items-center">
                                            <div class="form-group w-100">
                                                <label for="payfast_coupon"
                                                    class="form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="payfast_coupon" name="coupon"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto my-auto">
                                        <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                            data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                                class="fas fa-save"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="get-payfast-inputs"></div>
                        <div class="card-footer">
                            <div class="text-end">
                                <input type="hidden" name="plan_id" id="plan_id"
                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                <input type="submit" value="{{ __('Buy Now') }}" id="payfast-get-status"
                                    class="btn btn-xs btn-primary">

                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @endif
    {{-- Payfast end --}}

    {{-- Iyzipay --}}
    @if (isset($payment_setting['is_iyzipay_enabled']) && $payment_setting['is_iyzipay_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="iyzipay-payment" role="tabpanel"
            aria-labelledby="iyzipay-payment-tab">
            <div class="card-header">
                <h5>{{ __('Iyzipay') }}</h5>
            </div>

            @if (
                $payment_setting['is_iyzipay_enabled'] == 'on' &&
                    !empty($payment_setting['iyzipay_key']) &&
                    !empty($payment_setting['iyzipay_secret']) &&
                    !empty($payment_setting['iyzipay_mode']))
                <div
                    class="tab-pane {{ ($payment_setting['is_iyzipay_enabled'] == 'on' && !empty($payment_setting['iyzipay_key']) && !empty($payment_setting['iyzipay_secret'])) == 'on' ? 'active' : '' }}">
                    <form role="form" action="{{ route('iyzipay.payment.init') }}" method="post"
                        class="require-validation" id="iyzipay-form">
                        @csrf
                        <div class="card-body">
                            <div class="tab-pane " id="">
                                <input type="hidden" name="plan_id"
                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="d-flex align-items-center">
                                            <div class="form-group w-100">
                                                <label for="payfast_coupon"
                                                    class="form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="payfast_coupon" name="coupon"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-auto my-auto">
                                        <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                            data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                                class="fas fa-save"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="get-payfast-inputs"></div>
                        <div class="card-footer">
                            <div class="text-end">
                                <input type="hidden" name="plan_id" id="plan_id"
                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                <input type="submit" value="{{ __('Buy Now') }}" id=""
                                    class="btn btn-xs btn-primary">

                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    @endif
    {{-- Iyzipay end --}}
    {{-- sspay --}}
    @if (isset($payment_setting['is_sspay_enabled']) && $payment_setting['is_sspay_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="sspay-payment" role="tabpanel"
            aria-labelledby="sspay-payment-tab">
            <div class="card-header">
                <h5>{{ __('Sspay') }}</h5>
            </div>
            <form role="form" action="{{ route('sspay.prepare.plan') }}" method="post"
                class="require-validation" id="coingate-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- sspay end --}}
    {{-- Paytab --}}
    @if (isset($payment_setting['is_paytab_enabled']) && $payment_setting['is_paytab_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="paytab-payment" role="tabpanel" aria-labelledby="paytab-payment-tab">
            <div class="card-header">
                <h5>{{ __('Paytab') }}</h5>
            </div>
            <form role="form" action="{{ route('plan.pay.with.paytab') }}" method="post"
                class="require-validation" id="coingate-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Paytab end --}}
    {{-- Benefit --}}
    @if (isset($payment_setting['is_benefit_enabled']) && $payment_setting['is_benefit_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="benefit-payment" role="tabpanel"
            aria-labelledby="benefit-payment-tab">
            <div class="card-header">
                <h5>{{ __('Benefit') }}</h5>
            </div>
            <form role="form" action="{{ route('benefit.initiate') }}" method="post"
                class="require-validation" id="coingate-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Benefit end --}}
    {{-- Cashfree --}}
    @if (isset($payment_setting['is_cashfree_enabled']) && $payment_setting['is_cashfree_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="cashfree-payment" role="tabpanel"
            aria-labelledby="cashfree-payment-tab">
            <div class="card-header">
                <h5>{{ __('Cashfree') }}</h5>
            </div>
            <form role="form" action="{{ route('cashfree.payment') }}" method="post"
                class="require-validation" id="coingate-payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Cashfree end --}}
    {{-- Aamarpay --}}
    @if (isset($payment_setting['is_aamarpay_enabled']) && $payment_setting['is_aamarpay_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="aamarpay-payment" role="tabpanel"
            aria-labelledby="aamarpay-payment-tab">
            <div class="card-header">
                <h5>{{ __('Aamarpay') }}</h5>
            </div>
            <form role="form" action="{{ route('pay.aamarpay.payment') }}" method="post"
                class="require-validation" id="payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Aamarpay end --}}
    {{-- Paytr --}}
    @if (isset($payment_setting['is_paytr_enabled']) && $payment_setting['is_paytr_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="paytr-payment" role="tabpanel"
            aria-labelledby="paytr-payment-tab">
            <div class="card-header">
                <h5>{{ __('Pay TR') }}</h5>
            </div>
            <form role="form" action="{{ route('pay.paytr.payment') }}" method="post"
                class="require-validation" id="payment-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="toyyibpay_coupon" name="coupon"
                                        class="form-control coupon" data-from="toyyibpay"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Paytr end --}}

    {{-- Midtrans --}}
    @if (isset($payment_setting['is_midtrans_enabled']) && $payment_setting['is_midtrans_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="midtrans-payment" role="tabpanel"
            aria-labelledby="midtrans-payment-tab">
            <div class="card-header">
                <h5>{{ __('Midtrans') }}</h5>
            </div>
            <form role="form" action="{{ route('plan.get.midtrans') }}" method="post"
                class="require-validation" id="midtrans-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="midtrans_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="midtrans_coupon" name="coupon"
                                        class="form-control coupon" data-from="midtrans"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Midtrans end --}}

    {{-- Xendit --}}
    @if (isset($payment_setting['is_xendit_enabled']) && $payment_setting['is_xendit_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="xendit-payment" role="tabpanel"
            aria-labelledby="xendit-payment-tab">
            <div class="card-header">
                <h5>{{ __('Xendit') }}</h5>
            </div>
            <form role="form" action="{{ route('plan.xendit.payment') }}" method="post"
                class="require-validation" id="xendit-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="xendit_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="xendit_coupon" name="coupon"
                                        class="form-control coupon" data-from="xendit"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif


    {{-- YooKassa --}}
    @if (isset($payment_setting['is_yookassa_enabled']) && $payment_setting['is_yookassa_enabled'] == 'on')
        <div class="tab-pane fade border p-3" id="yookassa-payment" role="tabpanel"
            aria-labelledby="yookassa-payment-tab">
            <div class="card-header">
                <h5>{{ __('YooKassa') }}</h5>
            </div>
            <form role="form" action="{{ route('plan.pay.with.yookassa') }}" method="post"
                class="require-validation" id="yookassa-form">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="yookassa_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="yookassa_coupon" name="coupon"
                                        class="form-control coupon" data-from="yookassa"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        class="fas fa-save"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="submit" id="" value="{{ __('Buy Now') }}"
                            class="btn-create btn btn-lg btn-primary badge-blue">
                    </div>
                </div>
            </form>
        </div>
    @endif
    {{-- Paystack --}}
    @if (
        $payment_setting['is_paystack_enabled'] == 'on' &&
            !empty($payment_setting['paystack_public_key']) &&
            !empty($payment_setting['paystack_secret_key']))
        <div class="tab-pane fade border p-3" id="paystack-payment" role="tabpanel"
            aria-labelledby="paystack-payment">
            <div class="card-header">
                <h5>{{ __('Paystack') }}</h5>

            </div>

            <form class="w3-container w3-display-middle w3-card-4" method="POST" id="paystack-payment-form"
                action="{{ route('plan.pay.with.paystack') }}">
                @csrf
                <div class="card-body">
                    <div class="tab-pane " id="paystack_payment">
                        <input type="hidden" name="plan_id"
                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <label for="paypal_coupon" class="form-label">{{ __('Coupon') }}</label>
                                    <input type="text" id="paystack_coupon" name="coupon"
                                        class="form-control coupon" data-from="paystack"
                                        placeholder="{{ __('Enter Coupon Code') }}">
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <a href="#" class="apply-btn btn btn-lg btn-primary btn-create apply-coupon"
                                    data-toggle="tooltip" data-title="{{ __('Apply') }}"><i
                                        data-feather="save"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-end">
                        <input type="button" id="pay_with_paystack" value="{{ __('Buy Now') }}"
                            class="btn btn-lg btn-primary btn-create">
                    </div>
                </div>
            </form>


        </div>
    @endif
    {{-- Paystack end --}}

</div>
