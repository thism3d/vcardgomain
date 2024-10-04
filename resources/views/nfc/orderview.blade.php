@php
    $nfcImage = \App\Models\Utility::get_file('nfc/order_card_logo');
    $admin_payment_setting = Utility::getAdminPaymentSetting();
@endphp
<div class="row pb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Order ID') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->order_id }}
            </p>
        </div>
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Company Name') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->company_name }}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('NFC Card Name') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->nfc_card_name }}
            </p>
        </div>
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Business Name') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->business_name }}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Designation') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->designation }}
            </p>
        </div>
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Phone No') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->phoneno }}
            </p>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Email') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->email }}
            </p>
        </div>


        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Shipping Address') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->shipping_address }}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Order Status') }}</b></div>
            <p class="mb-4">
                {{ ucFirst($orderRequest->status) }}
            </p>
        </div>
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Payment Type') }}</b></div>
            <p class="mb-4">
                {{ __('Manually') }}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Quantity') }}</b></div>
            <p class="mb-4">
                {{ $orderRequest->quantity }}
            </p>
        </div>
        <div class="col-md-6">
            <div class="form-control-label"><b>{{ __('Total Price') }}</b></div>
            <p class="mb-4">
                {{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }}{{ $orderRequest->price }}
            </p>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="form-control-label"><b>{{ __('Card Logo') }}</b></div>
        </div>
        <div class="col-12 ">
            <div class="avatar-parent-child mb-3">
                <img class="" style="width: 200px;"
                    src="{{ isset($orderRequest->card_logo) && !empty($orderRequest->card_logo) ? $nfcImage . '/' . $orderRequest->card_logo : asset('custom/img/logo-placeholder-image-21.png') }}"
                    alt="">
            </div>

        </div>
    </div>



</div>

<div class="modal-footer p-0 pt-3">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

</div>
