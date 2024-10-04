@extends('layouts.admin')
@section('page-title')
    {{ __('Add-on Manager') }}
@endsection
@section('title')
    {{ __('Add-on Manager') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">
        {{ __('Add On') }}</li>
@endsection
@push('css-page')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        .system-version h5 {
            position: absolute;
            bottom: -44px;
            right: 27px;
        }

        .center-text {
            display: flex;
            flex-direction: column;
        }

        .center-text .text-primary {
            font-size: 14px;
            margin-top: 5px;
        }

        .theme-main {
            display: flex;
            align-items: center;
        }

        .theme-main .theme-avtar {
            margin-right: 15px;
        }

        @media only screen and (max-width: 575px) {
            .system-version h5 {
                position: unset;
                margin-bottom: 0px;
            }

            .system-version {
                text-align: center;
                margin-bottom: -22px;
            }
        }
    </style>
@endpush
@section('action-btn')
    <div class="text-end">
        <a href="{{ route('module.add') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title=""
            data-bs-original-title="{{ __('ModuleSetup') }}">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row justify-content-center px-0">
        <div class=" col-12">
            <div class="card">
                <div class="card-body package-card-inner  d-flex align-items-center">
                    <div class="package-itm theme-avtar">
                        <a href="https://workdo.io/vcardgo-saas-addon/" target="new">
                            <img src="https://workdo.io/wp-content/uploads/2023/03/favicon.jpg" alt="">
                        </a>
                    </div>
                    <div class="package-content flex-grow-1  px-3">
                        <h4>{{ __('Buy More Add-on') }}</h4>
                        <div class="text-muted">{{ __('+' . $addOnsCount . ' Premium Add-on') }}</div>
                    </div>
                    <div class="price text-end">
                        <a class="btn btn-primary" href="https://workdo.io/vcardgo-saas-addon/" target="new">
                            {{ __('Buy More Add-on') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] start -->
        <div class="event-cards row px-0">
            @if (count($modules)-1)<h3>{{ __('Installed Add-on') }}</h3>@endif
            @foreach ($modules as $module)
                @if ($module->getName() != 'LandingPage')
                    @php
                        $module_name = $module->getName();
                        $id = strtolower(preg_replace('/\s+/', '_', $module_name));
                        $path = $module->getPath() . '/module.json';
                        $json = json_decode(file_get_contents($path), true);
                    @endphp
                    @if (!isset($json['display']) || $json['display'] == true || $module_name == 'GoogleCaptcha')
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 product-card ">
                            <div class="card {{ $module->isEnabled() ? 'enable_module' : 'disable_module' }}">
                                <div class="product-img module-add-on-img">
                                    <div class="theme-main">
                                        <div class="theme-avtar">
                                            <img src="{{ \App\Models\Utility::get_module_img($module->getName()) }}"
                                                alt="{{ $module->getName() }}" class="img-user" style="max-width: 100%">
                                        </div>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="product-content-wrp">
                                        <div class="center-text">
                                            <div class="text-muted">
                                                @if ($module->isEnabled())
                                                    <span class="badge bg-success">{{ __('Enable') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Disable') }}</span>
                                                @endif
                                            </div>
                                            {{-- <small class="text-primary">{{ __('V')}}{{ sprintf("%.1f", $json['version']) }}</small> --}}
                                        </div>
                                        <h4 class="text-capitalize">
                                            {{ \App\Models\Utility::Module_Alias_Name($module->getName()) }}</h4>
                                        <p class="text-muted text-sm mb-0">
                                            {{ isset($json['description']) ? $json['description'] : '' }}
                                        </p>
                                        {{-- <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new" class="btn  btn-outline-secondary w-100 mt-2">{{ __('View Details')}}</a> --}}
                                    </div>
                                    <div class="module-add-on-checkbox">

                                        <div class="checkbox-custom">
                                            <div class="btn-group card-option">
                                                <button type="button" class="btn p-0" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                    @if ($module->isEnabled())
                                                        <a href="#!" class="dropdown-item module_change"
                                                            data-id="{{ $id }}">
                                                            <span>{{ __('Disable') }}</span>
                                                        </a>
                                                    @else
                                                        <a href="#!" class="dropdown-item module_change"
                                                            data-id="{{ $id }}">
                                                            <span>{{ __('Enable') }}</span>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('module.enable') }}" method="POST"
                                                        id="form_{{ $id }}">
                                                        @csrf
                                                        <input type="hidden" name="name"
                                                            value="{{ $module->getName() }}">
                                                    </form>
                                                    <a href="#"
                                                            class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><span
                                                                class="text-danger">{{ __('Remove') }}</span></a>

                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['module.remove', $module->getName()],
                                                            'id' => 'delete-form-' . $id,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
            <h3>{{ __('Explore Add-on') }}</h3>

            <div class="col-xl-12">
                @foreach ($category_wise_add_ons as $key => $category_wise_add_on)
                    <div id="tab-{{ $key }}" class="card add_on_manager">
                        <div class="card-body">
                            <div class="row">
                                @foreach ($category_wise_add_on as $key => $add_on)
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 product-card mb-2">
                                        <a href="{{ $add_on['url'] }}" target="_new" class="module-link">
                                            <div class=" enable_module manager-card">
                                                <div class="product-img module-add-on-img">
                                                    <div class="theme-main">
                                                        <div class="theme-avtar">
                                                            <a href="{{ $add_on['url'] }}" target="_new"> <img
                                                                    src="{{ $add_on['image'] }}" alt=""
                                                                    class="img-user" style="max-width: 100%">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="text-capitalize"> {{ $add_on['aliasname'] }}</h5>
                                                        @if ($add_on['buynow_status']==1)
                                                            <a href="javascript:;" class="module-preview"
                                                                data-bs-toggle="tooltip" title="Preview"
                                                                data-name="{{ $add_on['name'] }}"><span
                                                                    class="preview-icon"> <i
                                                                        class="ti ti-eye "></i></span></a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <a href="{{ $add_on['url'] }}" target="_new"
                                                        class="module-link btn btn-outline-secondary w-100 ">
                                                        @if ($add_on['buynow_status']==1)
                                                            {{ __('Buy Now') }}
                                                        @else
                                                            {{ __('Coming Soon') }}
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="hover-img-addon btn-primary">
            <button class="close-addon-btn">
                <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <path
                        d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                </svg>
            </button>
            <div class="swiper">
                <div class="module-swiper-container">
                    <div class="swiper-wrapper">
                        <!-- Swiper slides will be dynamically appended here -->
                    </div>
                </div>
                <span class="swiper-button-prev"></span>
                <span class="swiper-button-next"></span>
            </div>
        </div>
    </div>

    <div class="system-version">
        @php
            $version = config('verification.system_version');
        @endphp
        {{-- <h5 class="text-muted">{{ (!empty($version) ? 'V'.$version : '')}}</h5> --}}
    </div>
@endsection
@push('custom-scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


    <script>
        $(document).on('click', '.module_change', function() {
            var id = $(this).attr('data-id');
            $('#form_' + id).submit();
        });
    </script>

    <script>
        $(".module-preview").click(function() {
            $(".hover-img-addon").toggleClass("open");
            $("body").toggleClass("no-scroll");
        });
        $(".close-addon-btn").click(function() {
            $(".hover-img-addon").removeClass("open");
            $("body").removeClass("no-scroll");
        });
        $(".module-preview").click(function() {
            // Retrieve the add-on name from the data-name attribute
            var addOnName = $(this).data('name');
            var jsonData = {!! json_encode($category_wise_add_ons) !!};


            // Find the corresponding add-on object from your JSON data
            var addOn = jsonData.add_ons.find(function(item) {
                return item.name === addOnName;
            });

            // Populate the swiper container with preview images
            var swiperWrapper = $(".swiper-wrapper");
            swiperWrapper.empty(); // Clear previous images

            addOn.preview.forEach(function(imageUrl) {
                swiperWrapper.append('<div class="swiper-slide"><img src="' + imageUrl + '"></div>');
            });

            // Initialize Swiper
            var swiper = new Swiper('.module-swiper-container', {
                // Optional parameters
                slidesPerView: 1,
                loop: true,
                mousewheel: false,
                keyboard: {
                    enabled: true
                },

                // If you need pagination

                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
            });

            // Open the swiper modal or container
            // Example: $("#myModal").modal("show");
        });
    </script>
@endpush
