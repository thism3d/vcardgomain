@php
    $nfcImage = \App\Models\Utility::get_file('nfc/card_image');
    $admin_payment_setting = Utility::getAdminPaymentSetting();
@endphp
@extends('layouts.admin')
@section('page-title')
    {{ __('NFC Card') }}
@endsection
@section('title')
    {{ __('NFC Card') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('NFC Card') }}</li>
@endsection
@section('action-btn')
    @if (\Auth::user()->type == 'super admin')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a href="#" data-size="md" data-url="{{ route('nfc.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                title="{{ __('Create') }}" data-title="{{ __('Create New NFC Card') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @else
        <div
            class="col-xl-12 col-lg-12 col-md-12 align-items-center justify-content-between justify-content-md-end text-end">
            <a href="{{ route('order.request.index') }}" class="mx-3 btn btn-sm btn-primary" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Order History') }}"> <span class="text-white"> <i
                        class="ti ti-briefcase text-white"></i></span></a>
        </div>
    @endif
@endsection
@section('content')
    <div class="row">
        @if (\Auth::user()->type == 'super admin')
            @foreach ($nfcCardData as $key => $nfcCard)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <div class="row">
                                    <h6 class="nfc-card-title">{{ $nfcCard->card_name }}
                                    </h6>
                                    <span>{{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }}{{ $nfcCard->price }}</span>
                                </div>
                            </div>
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a data-url="{{ route('nfc.edit', $nfcCard->id) }}" data-size="md"
                                            data-ajax-popup="true" class="dropdown-item"
                                            data-bs-whatever="{{ __('Edit NFC Card') }}" data-bs-toggle="tooltip"
                                            data-title="{{ __('Edit NFC Card') }}"><i class="ti ti-pencil"></i>
                                            {{ __('Edit') }}</a>

                                        <a href="#"
                                            class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $nfcCard->id }}" title="{{ __('Delete') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top"><i
                                                class="ti ti-trash"></i>{{ __('Delete') }}</a>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['nfc.destroy', $nfcCard->id],
                                            'id' => 'delete-form-' . $nfcCard->id,
                                        ]) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2 justify-content-between">
                                <div class="col-12">
                                    <div class="text-center client-box">
                                        <div class="avatar-parent-child mb-3">
                                            <?php
                                            $imagePath = $nfcImage . '/' . $nfcCard->image;
                                            $headers = @get_headers($imagePath);
                                            ?>

                                            @if ($headers && strpos($headers[0], '200'))
                                                <img style="width: 300px;height: 200px;" class="rounded"
                                                    src="{{ isset($nfcCard->image) && !empty($nfcCard->image) ? $nfcImage . '/' . $nfcCard->image : asset('custom/nfcimg/nfc' . $nfcCard->id . '.png') }}"
                                                    alt="">
                                            @else
                                                <img style="width: 300px;height: 200px;" class="rounded"
                                                    src="{{ asset('custom/nfcimg/nfc' . $key + 1 . '.png') }}"
                                                    alt="">
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-md-3">
                <a class="btn-addnew-project" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Create New NFC Card') }}" data-url="{{ route('nfc.create') }}">
                    <div class="badge bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{ __('New NFC Card') }}</h6>
                    <p class="text-muted text-center">{{ __('Click here to add New NFC Card') }}</p>
                </a>
            </div>
        @else
            @foreach ($nfcCardData as $key => $nfcCard)
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 my-3">
                    <div class=" nfc-card-image1 border mx-auto box-shadow">


                        <?php
                        $imagePath = $nfcImage . '/' . $nfcCard->image;
                        $headers = @get_headers($imagePath);
                        ?>
                        @if ($headers && strpos($headers[0], '200'))
                            <img class="image"
                                src="{{ isset($nfcCard->image) && !empty($nfcCard->image) ? $nfcImage . '/' . $nfcCard->image : asset('custom/img/logo-placeholder-image-21.png') }}"
                                alt="">
                        @else
                            <img class="image" src="{{ asset('custom/nfcimg/nfc' . $key + 1 . '.png') }}" alt="">
                        @endif
                        <div class="info">
                            <div class="header">
                                <div class="title-info">
                                    <a data-url="{{ route('nfc.order', $nfcCard->id) }}" data-size="md" class="text-end"
                                        data-ajax-popup="true" class="dropdown-item"
                                        data-bs-whatever="{{ __('Order NFC Card') }}" data-bs-toggle="tooltip"
                                        data-title="{{ __('Order NFC Card') }}"><span class="price">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                                viewBox="0 0 19 19" fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M7.91797 15.8359C7.91797 17.1476 6.85465 18.2109 5.54297 18.2109C4.23129 18.2109 3.16797 17.1476 3.16797 15.8359C3.16797 14.5243 4.23129 13.4609 5.54297 13.4609C6.85465 13.4609 7.91797 14.5243 7.91797 15.8359ZM6.33464 15.8359C6.33464 16.2732 5.98019 16.6276 5.54297 16.6276C5.10574 16.6276 4.7513 16.2732 4.7513 15.8359C4.7513 15.3987 5.10574 15.0443 5.54297 15.0443C5.98019 15.0443 6.33464 15.3987 6.33464 15.8359Z"
                                                    fill="#545454"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M15.8346 15.8359C15.8346 17.1476 14.7713 18.2109 13.4596 18.2109C12.148 18.2109 11.0846 17.1476 11.0846 15.8359C11.0846 14.5243 12.148 13.4609 13.4596 13.4609C14.7713 13.4609 15.8346 14.5243 15.8346 15.8359ZM14.2513 15.8359C14.2513 16.2732 13.8969 16.6276 13.4596 16.6276C13.0224 16.6276 12.668 16.2732 12.668 15.8359C12.668 15.3987 13.0224 15.0443 13.4596 15.0443C13.8969 15.0443 14.2513 15.3987 14.2513 15.8359Z"
                                                    fill="#545454"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1.66578 2.01983C1.86132 1.62876 2.33685 1.47025 2.72792 1.66578L3.52236 2.06301C4.25803 2.43084 4.75101 3.15312 4.82547 3.97225L4.86335 4.38888C4.88188 4.59276 5.05283 4.74887 5.25756 4.74887H15.702C17.0838 4.74887 18.0403 6.12909 17.5551 7.42297L16.1671 11.1245C15.8195 12.0514 14.9333 12.6655 13.9433 12.6655H6.19479C4.96644 12.6655 3.94076 11.7289 3.82955 10.5056L3.24864 4.1156C3.22382 3.84255 3.05949 3.60179 2.81427 3.47918L2.01983 3.08196C1.62876 2.88643 1.47025 2.41089 1.66578 2.01983ZM5.47346 6.3322C5.2407 6.3322 5.05818 6.53207 5.07926 6.76388L5.40638 10.3622C5.44345 10.77 5.78534 11.0822 6.19479 11.0822H13.9433C14.2733 11.0822 14.5687 10.8775 14.6845 10.5685L16.0726 6.86702C16.1696 6.60825 15.9783 6.3322 15.702 6.3322H5.47346Z"
                                                    fill="#545454"></path>
                                            </svg>
                                        </span></a>

                                </div>
                                <p class="id m-0">
                                    {{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }}{{ $nfcCard->price }}
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endsection
