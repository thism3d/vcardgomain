@php
    $languages = App\Models\Utility::languages();
    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = Utility::getValByName('company_logo');
    $settings = App\Models\Utility::settings();
    $recaptcha = \App\Models\Utility::setCaptchaConfig();
    $landingpage_settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();

@endphp
@extends('layouts.auth')
@section('page-title')
    {{ __('Register') }}
@endsection

@section('language-bar')
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> {{ $languages[$lang] }}
                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach ($languages as $code => $language)
                    <a href="{{ route('register', [$ref, $code]) }}"tabindex="0"
                        class="dropdown-item {{ $code == $lang ? 'active' : '' }} ">
                        <span>{{ Str::ucFirst($language) }}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </div>
@endsection

@push('custom-scripts')
    @if ($settings['RECAPTCHA_MODULE'] == 'yes')
        @if (isset($settings['RECAPTCHA_VERSION']) && $settings['RECAPTCHA_VERSION'] == 'v2')
            {!! NoCaptcha::renderJs() !!}
        @else
            <script src="https://www.google.com/recaptcha/api.js?render={{ $settings['NOCAPTCHA_SITEKEY'] }}"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $settings['NOCAPTCHA_SITEKEY'] }}', {
                            action: 'submit'
                        }).then(function(token) {
                            document.getElementById('g-recaptcha-response').value = token;
                        });
                    });
                });
            </script>
        @endif
    @endif
@endpush

@section('content')
    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
        </div>
        {{ Form::open(['route' => 'register', 'method' => 'post', 'id' => 'loginForm']) }}
        <div class="custom-login-form">
            @if (session('status'))
                <div class="mb-4 font-medium text-lg text-green-600 text-danger">
                    {{ __('Email SMTP settings does not configured so please contact to your site admin.') }}
                </div>
            @endif
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Full Name') }}</label>
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Name')]) }}
                @error('name')
                    <span class="error invalid-name text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Email') }}</label>
                {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Email')]) }}
                @error('email')
                    <span class="error invalid-email text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Password') }}</label>
                {{ Form::password('password', ['class' => 'form-control', 'id' => 'input-password', 'placeholder' => __('Enter Your Password')]) }}

                @error('password')
                    <span class="error invalid-password text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('Confirm Password') }}</label>
                {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'confirm-input-password', 'placeholder' => __('Confirm Your Password')]) }}

                @error('password_confirmation')
                    <span class="error invalid-password_confirmation text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            @if ($settings['RECAPTCHA_MODULE'] == 'yes')
                @if (isset($settings['RECAPTCHA_VERSION']) && $settings['RECAPTCHA_VERSION'] == 'v2')
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        {!! NoCaptcha::display($settings['cust_darklayout'] == 'on' ? ['data-theme' => 'dark'] : []) !!}
                        @error('g-recaptcha-response')
                            <span class="small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @else
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                        @error('g-recaptcha-response')
                            <span class="error small text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                @endif
            @endif
            <div class="form-check custom-checkbox">
                <input type="checkbox" class="form-check-input" id="termsCheckbox" name="terms" required="">
                <label class="form-check-label text-sm" for="termsCheckbox">{{ __('I agree to the') }}
                    @if ($landingpage_settings['menubar_status'] == 'on')
                        @if (is_array(json_decode($landingpage_settings['menubar_page'])) ||
                                is_object(json_decode($landingpage_settings['menubar_page'])))
                            @foreach (json_decode($landingpage_settings['menubar_page']) as $key => $value)
                                @if ($value->menubar_page_name == 'Terms and Conditions')
                                    @if (isset($value->login) &&
                                            $value->login == 'on' &&
                                            (isset($value->template_name) && $value->template_name == 'page_content'))
                                        <a class="" target="_blank"
                                            href="{{ route('custom.page', $value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                                    @elseif (isset($value->login) &&
                                            $value->login == 'on' &&
                                            (isset($value->template_name) && $value->template_name == 'page_url'))
                                        <a class="" target="_blank"
                                            href="{{ $value->page_url }}">{{ $value->menubar_page_name }}</a>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endif
                    {{ __('and the') }}
                    @if ($landingpage_settings['menubar_status'] == 'on')
                        @if (is_array(json_decode($landingpage_settings['menubar_page'])) ||
                                is_object(json_decode($landingpage_settings['menubar_page'])))
                            @foreach (json_decode($landingpage_settings['menubar_page']) as $key => $value)
                                @if ($value->menubar_page_name == 'Privacy Policy')
                                    @if (isset($value->login) &&
                                            $value->login == 'on' &&
                                            (isset($value->template_name) && $value->template_name == 'page_content'))
                                        <a class="" target="_blank"
                                            href="{{ route('custom.page', $value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                                    @elseif (isset($value->login) &&
                                            $value->login == 'on' &&
                                            (isset($value->template_name) && $value->template_name == 'page_url'))
                                        <a class="" target="_blank"
                                            href="{{ $value->page_url }}">{{ $value->menubar_page_name }}</a>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endif




                </label>
            </div>
            <div class="d-grid">
                <button class="btn btn-primary btn-block mt-2">{{ __('Register') }}</button>
            </div>
            @if (Utility::getValByName('signup_button') == 'on')
                <p class="my-4 text-center">{{ __('Already have an account?') }}
                    <a href="{{ route('login', $lang) }}" tabindex="0">{{ __('Login') }}</a>
                </p>
            @endif
            <input type="hidden" name="referral_code" class="referral_code" value="{{ $ref }}" />
        </div>
        {{ Form::close() }}

    </div>
@endsection
