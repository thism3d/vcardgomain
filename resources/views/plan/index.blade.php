@extends('layouts.admin')
@php
    $dir = asset(Storage::url('uploads/plan'));
    $admin_payment_setting = Utility::getAdminPaymentSetting();

@endphp
@section('page-title')
    {{ __('Plans') }}
@endsection

@section('title')
    {{ __('Manage Plan') }}
@endsection
@section('action-btn')
    @can('create plan')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            @if (App\Models\Utility::getPaymentIsOn() && \Auth::user()->type == 'super admin')
                <a href="#" data-size="lg" data-url="{{ route('plans.create') }}" data-ajax-popup="true"
                    data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Plan') }}"
                    class="btn btn-sm btn-primary">
                    <i class="ti ti-plus"></i>
                </a>
            @endif
        </div>
    @endcan
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Plans') }}</li>
@endsection
@section('content')
    <div class="row">
        @foreach ($plans as $plan)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                <div class="plan_card">
                    <div class="card price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        <div class="card-body">
                            <span class="price-badge bg-primary">{{ $plan->name }}</span>
                            @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                <div class="d-flex flex-row-reverse m-0 p-0 ">
                                    <span class="d-flex align-items-center ">
                                        <i class="f-10 lh-1 fas fa-circle text-success"></i>
                                        <span class="ms-2">{{ __('Active') }}</span>
                                    </span>
                                </div>
                            @endif
                            @if (\Auth::user()->type == 'super admin')
                                @can('edit plan')
                                    <div class="row d-flex  ">
                                        <div class="col-6 text-start">
                                            <div class="action-btn  ms-2">
                                                @if ($plan->id != 1)
                                                    <div class="form-check form-switch custom-switch-v1 float-end">
                                                        <input type="checkbox" name="plan_active"
                                                            class="form-check-input input-primary is_plan_active" value="1"
                                                            data-id="{{ $plan->id }}" data-name="plan"
                                                            {{ $plan->is_plan_enable == 'on' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="plan_active"></label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="action-btn bg-primary ms-2">
                                                <a data-url="{{ route('plans.edit', $plan->id) }}" data-size="lg"
                                                    data-ajax-popup="true" data-bs-placement="top" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Edit') }}"
                                                    data-title="{{ __('Edit Plan') }}" data-toggle="tooltip"
                                                    data-original-title="{{ __('Edit') }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>

                                            @if ($plan->id != 1)
                                                <div class="action-btn bg-danger ms-2">
                                                    <a href="#"
                                                        class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $plan->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><span class="text-white"><i
                                                                class="ti ti-trash"></i></span></a>
                                                </div>
                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['plans.destroy', $plan->id],
                                                    'id' => 'delete-form-' . $plan->id,
                                                ]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </div>
                                    </div>
                                @endcan
                            @endif
                            <span class="mb-4 p-price m"><span
                                    style="font-weight: 600">{{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }}{{ $plan->price }}</span><small
                                    class="text-sm">{{ __('/ Duration : ') . __(ucfirst($plan->duration)) }}</small></span>
                            <p class="mb-0">
                                {{ 'Free Trial Day : ' }}{{ $plan->trial_day }}
                            </p>
                            <p class="mb-0">
                                {{ $plan->description }}
                            </p>




                            <ul class="list-unstyled my-4">
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    {{ count($plan->getThemes()) }} {{ __('Themes') }}
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    {{ $plan->business == '-1' ? 'Unlimited' : $plan->business }} {{ __('Business') }}
                                </li>
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i></span>
                                    {{ $plan->max_users == '-1' ? 'Unlimited' : $plan->max_users }} {{ __('Users') }}
                                </li>
                                @if ($plan->enable_custdomain == 'on')
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ __('Custom Domain') }}
                                    </li>
                                @else
                                    <li>

                                        <span class="theme-avtar">
                                            <i data-feather="x" class="text-danger"></i></span>
                                        <span class="text-danger"> {{ __('Custom Domain') }}</span>

                                    </li>
                                @endif
                                @if ($plan->enable_custsubdomain == 'on')
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ __('Sub Domain') }}
                                    </li>
                                @else
                                    <li>
                                        <span class="theme-avtar">
                                            <i data-feather="x" class="text-danger"></i></span>
                                        <span class="text-danger"> {{ __('Sub Domain') }}</span>

                                    </li>
                                @endif
                                @if ($plan->enable_branding == 'on')
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ __('Branding') }}
                                    </li>
                                @else
                                    <li>
                                        <span class="theme-avtar">
                                            <i data-feather="x" class="text-danger"></i></span>
                                        <span class="text-danger">{{ __('Branding') }}</span>
                                    </li>
                                @endif
                                @if ($plan->pwa_business == 'on')
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ __('Progressive Web App (PWA)') }}
                                    </li>
                                @else
                                    <li>
                                        <span class="theme-avtar">
                                            <i data-feather="x" class="text-danger"></i></span>
                                        <span class="text-danger">{{ __('Progressive Web App (PWA)') }}</span>
                                    </li>
                                @endif
                                @if ($plan->enable_chatgpt == 'on')
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ __('Chatgpt') }}
                                    </li>
                                @else
                                    <li>
                                        <span class="theme-avtar">
                                            <i data-feather="x" class="text-danger"></i></span>
                                        <span class="text-danger">{{ __('Chatgpt') }}</span>
                                    </li>
                                @endif
                                <li>
                                    <span class="theme-avtar">
                                        <i class="text-primary ti ti-circle-plus"></i>
                                    </span>
                                    {{ $plan->storage_limit }} {{ __('MB Storage Limit') }}
                                </li>
                                @if ($plan->module)
                                    <h6 class="text-muted">{{__('Add On')}}</h6>
                                    @foreach ($modules as $module)
                                        @php
                                            $id = strtolower(preg_replace('/\s+/', '_', $module->getName()));
                                            $path = $module->getPath() . '/module.json';
                                            $json = json_decode(file_get_contents($path), true);
                                            $plan_modules = explode(',', $plan->module);
                                        @endphp
                                        @if (!isset($json['display']) || $json['display'] == true)
                                            @if ($module->getName() != 'LandingPage')
                                                @if (in_array($module->getName(), $plan_modules))
                                                    <li>
                                                        <span class="theme-avtar">
                                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                                        {{ \App\Models\Utility::Module_Alias_Name($module) }}
                                                    </li>
                                                @else
                                                    <li>
                                                        <span class="theme-avtar">
                                                            <i data-feather="x" class="text-danger"></i></span>
                                                        {{ \App\Models\Utility::Module_Alias_Name($module) }}
                                                    </li>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </ul>

                            @if (\Auth::user()->type == 'company' && \Auth::user()->trial_expire_date)
                                @if (
                                    (\Auth::user()->type == 'company' && \Auth::user()->is_trial_plan == $plan->id) ||
                                        \Auth::user()->plan_expire_date > date('Y-m-d'))
                                    <p class="plan-expired text-dark mb-0">
                                        {{ __('Plan Trial Expired : ') }}
                                        {{ !empty(\Auth::user()->trial_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->trial_expire_date) : 'lifetime' }}
                                    </p>
                                @endif
                            @else
                                @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                                    <p class="plan-expired text-dark mb-0">
                                        {{ __('Plan Expired : ') }}
                                        {{ !empty(\Auth::user()->plan_expire_date) ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : 'lifetime' }}
                                    </p>
                                @endif
                            @endif
                            <div class="row d-flex justify-content-between">
                                <div class="col-12  pb-1">
                                    @if ($plan->is_trial == 'on' && \Auth::user()->type != 'super admin')
                                        @if (\Auth::user()->is_trial_plan == 0 && \Auth::user()->trial_expire_date == null && \Auth::user()->plan != $plan->id)
                                            <div class="d-grid text-center">
                                                <a href="{{ route('trial.period', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                    class="btn btn-primary btn-md d-flex justify-content-center align-items-center">{{ __('Start Trial Days') }}</a>
                                                <p></p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row d-flex justify-content-between">
                                <div class="col-8">
                                    @if (
                                        \Auth::user()->type == 'company' &&
                                            (empty(\Auth::user()->plan_expire_date) || \Auth::user()->plan_expire_date < date('Y-m-d')))
                                        @if (App\Models\Utility::getPaymentIsOn())
                                            @if (\Auth::user()->type == 'company' && \Auth::user()->plan != $plan->id)
                                                @if ($plan->price > 0)
                                                    @can('buy plan')
                                                        <div class="d-grid text-center">
                                                            <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                                class="btn  btn-primary d-flex justify-content-center align-items-center ">{{ __('Subscribe') }}
                                                                <i class="fas fa-arrow-right m-1"></i></a>
                                                            <p></p>
                                                        </div>
                                                    @endcan
                                                @endif
                                            @endif
                                        @endif
                                    @else
                                        @if (App\Models\Utility::getPaymentIsOn())
                                            @if ($plan->id != \Auth::user()->plan && \Auth::user()->type == 'company')
                                                @if ($plan->price > 0)
                                                    @can('buy plan')
                                                        <div class="d-grid text-center">
                                                            <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                                class="btn btn-primary btn-md d-flex justify-content-center align-items-center">{{ __('Subscribe') }}
                                                                <i class="ti ti-arrow-right ms-1"></i></a>
                                                            <p></p>
                                                        </div>
                                                    @endcan
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                </div>
                                @if (\Auth::user()->type != 'super admin' && \Auth::user()->plan != $plan->id)
                                    @if ($plan->id != 1)
                                        @if (\Auth::user()->requested_plan != $plan->id)
                                            <div class="col-4">
                                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                    class="btn btn-primary btn-icon btn-md"
                                                    data-title="{{ __('Send Request') }}" data-bs-placement="top"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Send Request') }}"
                                                    data-toggle="tooltip">
                                                    <span class="btn-inner--icon"><i
                                                            class="ti ti-arrow-forward-up"></i></span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="col-4">
                                                <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                                    class="btn btn-icon  btn-danger btn-md" data-bs-placement="top"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Cancel Request') }}">
                                                    <span class="btn-inner--icon"><i class="ti ti-x"></i></span>
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@push('custom-scripts')
    <script>
        $(document).on("click", ".is_plan_active", function() {
            var id = $(this).attr('data-id');
            var is_disable = ($(this).is(':checked')) ? $(this).val() : 0;

            $.ajax({
                url: '{{ route('plan.enable') }}',
                type: 'POST',
                data: {
                    "is_disable": is_disable,
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.is_success == true) {
                        toastrs('{{ __('Success') }}', data.msg, 'success');
                    } else if (data.is_success == false) {
                        toastrs('{{ __('Error') }}', data.msg, 'error');
                        $('.is_plan_active[data-id="' + id + '"]').prop('checked', !is_disable);
                    }
                    if (is_disable == 0) {
                        $('#link_' + id).addClass('row-disabled');
                    } else {
                        $('#link_' + id).removeClass('row-disabled');
                    }

                }
            });
        });
    </script>
@endpush
