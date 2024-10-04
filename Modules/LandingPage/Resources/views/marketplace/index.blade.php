@php
    use App\Models\Utility;
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $allSettings = Utility::settings();
    $landinglogo = Utility::get_file('uploads/landing_page_image');

    $sup_logo = Utility::get_file('uploads/logo');
    $setting = \App\Models\Utility::colorset();
    $SITE_RTL = Utility::getValByName('SITE_RTL');

    $metatitle = isset($allSettings['meta_title']) ? $allSettings['meta_title'] : '';
    $metsdesc = isset($allSettings['meta_desc']) ? $allSettings['meta_desc'] : '';
    $meta_image = \App\Models\Utility::get_file('uploads/meta/');
    $meta_logo = isset($allSettings['meta_image']) ? $allSettings['meta_image'] : '';
    $admin_payment_setting = \App\Models\Utility::getAdminPaymentSetting();
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if (isset($setting['color_flag']) && $setting['color_flag'] == 'true') {
        $themeColor = 'custom-color';
    } else {
        $themeColor = $color;
    }
    $category_path = \App\Models\Utility::get_file('category');
    $banner = \App\Models\Utility::get_file('card_banner');
    $logo = \App\Models\Utility::get_file('card_logo');
@endphp
<!DOCTYPE html>
{{-- <html lang="en"> --}}
<html lang="en" dir="{{ $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <style>
        :root {
            --color-customColor: <?=$color ?>;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
    <title>{{ env('APP_NAME') }}</title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />

    <meta name="title" content="{{ $metatitle }}">
    <meta name="description" content="{{ $metsdesc }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $metatitle }}">
    <meta property="og:description" content="{{ $metsdesc }}">
    <meta property="og:image" content="{{ $meta_image . $meta_logo }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $metatitle }}">
    <meta property="twitter:description" content="{{ $metsdesc }}">
    <meta property="twitter:image" content="{{ $meta_image . $meta_logo }}">

    <!-- Favicon icon -->
    <link rel="icon" href="{{ $sup_logo . '/' . $allSettings['company_favicon'] }}" type="image/x-icon" />

    {{-- <link rel="icon" href="{{ $logo . '/favicon.png' }}" type="image/png"> --}}


    <!-- font css -->
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="  {{ Module::asset('LandingPage:Resources/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ Module::asset('LandingPage:Resources/assets/fonts/material.css') }}" />



    <!-- vendor css -->
    <link rel="stylesheet" href="  {{ Module::asset('LandingPage:Resources/assets/css/style.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/customizer.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/landing-page.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/marketplace.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/custom.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">


    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif

    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ Module::asset('LandingPage:Resources/assets/css/style.css') }}"
            id="main-style-link">
    @endif
    @if ($setting['cust_darklayout'] == 'on')

<body class="{{ $themeColor }} landing-dark">
@else

    <body class="{{ $themeColor }}">
        @endif
        </head>

        <body class="theme-2">
            <header class="main-header header-two">
                @if ($settings['menubar_status'] == 'on')
                    <div class="container">
                        <nav class="navbar navbar-expand-md  default top-nav-collapse">
                            <div class="header-left">
                                <a class="navbar-brand bg-transparent" href="#">
                                    <img src="{{ $landinglogo . '/' . $settings['site_logo'] }}" alt="logo">
                                </a>
                            </div>
                            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link active"
                                            href="{{ url('/') }}">{{ $settings['home_title'] }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ url('/#features') }}">{{ $settings['feature_title'] }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ url('/#plan') }}">{{ $settings['plan_title'] }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ url('/#faq') }}">{{ $settings['faq_title'] }}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#business-tab">{{ __('Business') }}</a>
                                    </li>

                                    @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
                                        @foreach (json_decode($settings['menubar_page']) as $key => $value)
                                            @if ($value->page_url != null && $value->header == 'on')
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ url($value->page_url) }}"
                                                        target="_blank">{{ $value->menubar_page_name }}</a>
                                                </li>
                                            @endif
                                            @if ($value->header == 'on' && $value->page_url == null)
                                                <li class="nav-item">
                                                    <a class="nav-link"
                                                        href="{{ route('custom.page', $value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif


                                </ul>
                                <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                            <div class="ms-auto d-flex justify-content-end gap-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-dark rounded"><span
                                        class="hide-mob me-2">{{ __('Login') }}</span> <i
                                        data-feather="log-in"></i></a>
                                <a href="{{ route('register') }}" class="btn btn-outline-dark rounded"><span
                                        class="hide-mob me-2">{{ __('Register') }}</span> <i
                                        data-feather="user-check"></i></a>
                                <button class="navbar-toggler " type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                                    aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                            </div>
                        </nav>
                    </div>
                @endif

            </header>
            <div class="site-content">
                <div class="dash-landing-wrapper">
                    <section class="bg-dark-green product-banner-section vcardgo-addon-banner">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <nav class="woocommerce-breadcrumb" aria-label="breadcrumbs">
                                        <a href="{{ route('landingpage.backhome') }}">Home</a><span
                                            class="breadcrumb-separator"> <small> &gt; </small>
                                        </span>vCardGo Marketplace
                                    </nav>
                                    <div class="title-content-inner dash-banner-title">
                                        <div class="section-title ">
                                            <h1>
                                                Discover<span class="font-dash"> New Categories, </span>with <br>
                                                vCardGo -<span class="vcard-banner-content"> Browse Beyond Limits!
                                            </h1>
                                        </div>
                                        <form role="search" method="get" class="search-form" id="product_search"
                                            action="{{ route('business.search') }}">
                                            <div class="search_box search-box">
                                                <input type="hidden" name="post_type" value="product">
                                                <input type="search" class="search-field"
                                                    placeholder="Search for any Business..."
                                                    value="{{ isset($_GET['search-business']) ? $_GET['search-business'] : '' }}"
                                                    name="search-business">
                                                {!! Form::select('category', $categoryList, $selectedCategory ?? null, [
                                                    'class' => 'form-control select2 business_category select-box ',
                                                    'required' => 'required',
                                                ]) !!}

                                                @if (isset($_GET['search-business']) && $_GET['search-business'] != '')
                                                    <a href="{{ route('marketplace.home') }}"><i
                                                            class="ti ti-refresh text-black"></i></a>
                                                @endif

                                                <button type="submit" class="btn search-btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                        height="18" viewBox="0 0 18 18" fill="none">
                                                        <g clip-path="url(#clip0_1709_2846)">
                                                            <path
                                                                d="M17.8296 17.0207L13.1823 12.4477C14.3992 11.1255 15.147 9.37682 15.147 7.45261C15.1464 3.33639 11.756 0 7.57339 0C3.39078 0 0.000366211 3.33639 0.000366211 7.45261C0.000366211 11.5688 3.39078 14.9052 7.57339 14.9052C9.38057 14.9052 11.0381 14.2802 12.34 13.241L17.0054 17.832C17.2327 18.056 17.6017 18.056 17.829 17.832C18.0569 17.6081 18.0569 17.2447 17.8296 17.0207ZM7.57339 13.7586C4.03445 13.7586 1.16558 10.9353 1.16558 7.45261C1.16558 3.96991 4.03445 1.14663 7.57339 1.14663C11.1124 1.14663 13.9812 3.96991 13.9812 7.45261C13.9812 10.9353 11.1124 13.7586 7.57339 13.7586Z"
                                                                fill="white" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_1709_2846">
                                                                <rect width="18" height="18" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <span>{{ __('Search') }}</span>
                                                </button>

                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="img-wrapper ">
                                        <img src="{{ asset('custom/img/banner-round.png') }}" alt="banner-image"
                                            loading="lazy" width="100%" height="100% ">
                                    </div>
                                </div>
                            </div>
                            <img src="https://workdo.io/wp-content/themes/storefront-child/assets/images/bookinggo-saas-addon/banner-bg-image.png"
                                alt="banner-bg-image" loading="lazy" class="vcard-banner-bg">
                        </div>
                    </section>
                    <section class="directory-category-section">
                        <div class="container">
                            <div class="section-title text-center">
                                <h2>{{ __('Top Categories') }}</h2>
                                <p>{{ __('Discover a diverse range of top categories on vCardgo.Find evenything you need in one convenient place.') }}
                                </p>
                            </div>
                            <div class="swiper category-slider">
                                <div class="swiper-wrapper">
                                    @foreach ($categoryData as $key => $category)
                                        <div class="swiper-slide">
                                            <div class="category-body-div">
                                                <div class="category-logo">
                                                    <div class="cat-img">
                                                        <div class="cat-img-div-main">
                                                            <div class="cat-img-div">
                                                                <?php
                                                                $imagePath = $category_path . '/' . $category->logo;
                                                                $headers = @get_headers($imagePath);
                                                                ?>
                                                                @if ($headers && strpos($headers[0], '200'))
                                                                    <img src="{{ !empty($category->logo) ? $category_path . '/' . $category->logo : asset('custom/img/placeholder-image21.jpg') }}"
                                                                        class="rounded-circle img_categorys_fix_size"
                                                                        width="100px" height="100px">
                                                                @else
                                                                    <img class="rounded"
                                                                        src="{{ asset('custom/category/category' . $key + 1 . '.png') }}"
                                                                        alt="" width="100px" height="100px">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="category-name">
                                                    <h6>{{ $category->name }}</h6>
                                                    <span>{{ $category->count . ' Business' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                {{-- .tab   - --}}
                                <div class="tab-btn-wrapper">
                                    <div class="swiper-button-prev">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="90" height="16"
                                            viewBox="0 0 64 16" fill="none">
                                            <path
                                                d="M0.383912 8.70711C-0.0066124 8.31658 -0.0066124 7.68342 0.383912 7.29289L6.74787 0.928932C7.1384 0.538408 7.77156 0.538408 8.16209 0.928932C8.55261 1.31946 8.55261 1.95262 8.16209 2.34315L2.50523 8L8.16209 13.6569C8.55261 14.0474 8.55261 14.6805 8.16209 15.0711C7.77156 15.4616 7.1384 15.4616 6.74787 15.0711L0.383912 8.70711ZM63.2734 9H1.09102V7H63.2734V9Z"
                                                fill="white"></path>
                                        </svg>
                                    </div>
                                    <div class="swiper-button-next">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="90" height="16"
                                            viewBox="0 0 64 16" fill="none">
                                            <path
                                                d="M63.7059 8.70711C64.0965 8.31658 64.0965 7.68342 63.7059 7.29289L57.342 0.928932C56.9514 0.538408 56.3183 0.538408 55.9278 0.928932C55.5372 1.31946 55.5372 1.95262 55.9278 2.34315L61.5846 8L55.9278 13.6569C55.5372 14.0474 55.5372 14.6805 55.9278 15.0711C56.3183 15.4616 56.9514 15.4616 57.342 15.0711L63.7059 8.70711ZM0.816406 9H62.9988V7H0.816406V9Z"
                                                fill="white"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="directory-discover-section" id="business-tab">
                        <div class="container">
                            <div class="section-title-wrp d-flex">
                                <div class="section-title text-center">
                                    <h2>{{ __('Discover Business') }}</h2>
                                    <p>{{ __('Dive into vCardGo`s extensive array of categories, covering everything from essential services to leisure activities. Explore and discover the perfect solutions for all your needs in one convenient platform.') }}
                                    </p>
                                </div>
                                <form id="sortForm" method="GET" action="{{ route('marketplace.home') }}">
                                    <select name="orderby" id="sort_by" class="form-control category-option"
                                        onchange="document.getElementById('sortForm').submit();">
                                        <option value="">Sort By</option>
                                        <option value="latest" {{ request('orderby') == 'latest' ? 'selected' : '' }}>
                                            Latest</option>
                                        <option value="popularity"
                                            {{ request('orderby') == 'popularity' ? 'selected' : '' }}>Popularity
                                        </option>
                                    </select>
                                </form>
                            </div>

                            <div class="row business-main">
                                @foreach ($businessDetail as $key => $business)
                                    <div class="col-xl-3 col-lg-4  col-sm-6 col-12 d-flex">
                                        <div class="product-card d-flex">
                                            <div class="product-card-inner">
                                                <div class="feature-img">
                                                    <img src="{{ isset($business->banner) && !empty($business->banner) ? $banner . '/' . $business->banner : asset('custom/img/placeholder-image1.jpg') }}"
                                                        alt="images" class=" imagepreview home-banner"
                                                        id="banner">
                                                </div>

                                                <div class="product-content">
                                                    <div class="product-content-top">
                                                        <div class="business-logo-two">
                                                            <img src="{{ isset($business->logo) && !empty($business->logo) ? $logo . '/' . $business->logo : asset('custom/img/placeholder-image1.jpg') }}"
                                                                alt="images" class="imagepreview" id="banner">
                                                        </div>

                                                        <a href="javascript:void(0);" tabindex="0">
                                                            <h5>{{ ucFirst($business->title) }}</h5>
                                                        </a>
                                                        <p>{{ !empty($business->description) ? ucFirst($business->description) : '--' }}
                                                        </p>
                                                    </div>
                                                    <div class="product-content-bottom">
                                                        <!-- Your rating content here -->

                                                        <div class="card-bottom d-flex align-items-center justify-content-between">

                                                            <a href="{{ url('/' . $business->slug) }}"
                                                                page-name="Listing Page" target="_blank"
                                                                class=" btn-preview btn">
                                                                Open Business
                                                            </a>
                                                            <div class="d-flex align-items-center gap-1">
                                                                <a class="btn tooltip-btn rounded tooltip-btn"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="bottom" data-size="lg"
                                                                    data-title="{{ __('Preview') }}"
                                                                    title="{{ __('Preview') }}"
                                                                    data-bs-original-title="{{ __('Preview') }}"
                                                                    data-url="{{ route('bussiness.view', $business->slug) }}"
                                                                    data-ajax-popup="true"><span class="text-white"><i
                                                                            class="ti ti-eye"></i></span></a>
                                                                <a  data-size="md"
                                                                    data-url="{{ route('bussiness.share', $business->slug) }}"
                                                                    data-bs-toggle="tooltip" data-size="md"
                                                                    data-ajax-popup="true"
                                                                    title="{{ __('Share') }}"
                                                                    data-title="{{ __('Share') }}"
                                                                    title="{{ __('Share') }}"
                                                                    class="btn tooltip-btn btn-sm"><span
                                                                        class="text-white"><i
                                                                            class="ti ti-share "></i></span></a>
                                                                </a>
                                                                <a href="{{ route('bussiness.save', $business->slug) }}"
                                                                    page-name="Listing Page" class=" tooltip-btn btn">
                                                                    <span class="text-white"><i
                                                                            class="ti ti-download"></i></span></a>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </div>
            </div>



            <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modelCommanModelLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                    </div>
                </div>
            </div>

            <script src="{{ asset('custom/js/jquery.min.js') }}"></script>
            <script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/popper.min.js') }}"></script>
            <script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/bootstrap.min.js') }}"></script>
            <script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/feather.min.js') }}"></script>
            <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
            <script src="{{ asset('custom/js/custom.js') }}"></script>

            <script>
                var swiper = new Swiper('.category-slider', {
                    // Optional parameters
                    spaceBetween: 20,
                    loop: true,
                    mousewheel: false,
                    keyboard: {
                        enabled: true
                    },
                    breakpoints: {
                        1440: {
                            slidesPerView: 9,
                        },
                        1199: {
                            slidesPerView: 7,
                        },
                        991: {
                            slidesPerView: 5,
                        },
                        768: {
                            slidesPerView: 4,
                        },
                        575: {
                            slidesPerView: 3,
                        },
                        0: {
                            slidesPerView: 1,
                        }
                    },

                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev"
                    },
                });
                feather.replace();
            </script>
            @if ($allSettings['enable_cookie'] == 'on')
                @include('layouts.cookie_consent')
            @endif

        </body>

</html>
