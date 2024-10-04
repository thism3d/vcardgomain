@php
    $cardLogo = \App\Models\Utility::get_file('card_logo');
@endphp
@extends('layouts.admin')
@section('page-title')
    {{ __('Business') }}
@endsection
@section('title')
    {{ __('Business') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Business') }}</li>
@endsection
@section('action-btn')
    @can('create business')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a href="#" data-size="xl" data-url="{{ route('business.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Business') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection
@section('content')
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1" style="">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['business.index'], 'method' => 'get', 'id' => 'business_filter']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('business', __('Name'), ['class' => 'form-label']) }}
                                <input type="text" name="business" class="form-control"
                                    value="{{ isset($_GET['business']) ? $_GET['business'] : '' }}"
                                    placeholder ="Enter a business name">
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('start_date', __('Start date'), ['class' => 'form-label']) }}
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ isset($_GET['start_date']) ? $_GET['start_date'] : '' }}" placeholder ="">
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('end_date', __('End date'), ['class' => 'form-label']) }}
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ isset($_GET['end_date']) ? $_GET['end_date'] : '' }}" placeholder ="">
                            </div>
                        </div>

                        <div class="col-auto float-end ms-2 mt-4">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('business_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('business.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                title="" data-bs-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Business Logo') }}</th>
                                    <th>{{ __('Businesses') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Generate Date') }}</th>
                                    <th>{{ __('Directory') }}</th>
                                    <th class="text-end">{{ __('Operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($business as $val)
                                    <tr class="{{ $val->admin_enable == 'off' ? 'row-disabled' : '' }}">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <div class="avatar">
                                                <img style="width: 55px;height: 55px;" class="rounded-circle "
                                                    src="{{ isset($val->logo) && !empty($val->logo) ? $cardLogo . '/' . $val->logo : asset('custom/img/logo-placeholder-image-21.png') }}"
                                                    alt="">
                                            </div>
                                        </td>

                                        <td class="{{ $val->status == 'locked' ? 'row-disabled' : '' }}">
                                            <a class="" href="{{ route('business.edit', $val->id) }}"
                                                class=""><b>{{ ucFirst($val->title) }}</b></a>
                                        </td>
                                        <td><span
                                                class="badge fix_badge @if ($val->status == 'locked') bg-danger @else bg-info @endif p-2 px-3 rounded">{{ ucFirst($val->status) }}</span>
                                        </td>

                                        @php
                                            $now = $val->created_at;
                                            $date = $now->format('Y-m-d');
                                            $time = $now->format('H:i:s');
                                        @endphp
                                        <td>{{ $val->created_at }}</td>
                                        <td><span
                                                class="badge fix_badge @if ($val->directory_status == 'off') bg-danger @else bg-info @endif p-2 px-3 rounded">{{ $val->directory_status == 'on' ? 'Active' : 'Deactive' }}</span>
                                        </td>
                                        <td class="text-end">

                                            <div class="action-btn bg-dark ms-2">
                                                <a href="#"
                                                    class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-confirm="{{ __('You want to confirm this action') }}"
                                                    data-text="{{ __('Press Yes to continue or No to go back') }}"
                                                    data-confirm-yes="duplicate-form-{{ $val->id }}"
                                                    title="{{ __('Duplicate') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><span class="text-white"><i
                                                            class="ti ti-copy"></i></span></a>
                                                {!! Form::open([
                                                    'method' => 'POST',
                                                    'route' => ['business.duplicate', $val->id],
                                                    'id' => 'duplicate-form-' . $val->id,
                                                ]) !!}
                                                {!! Form::close() !!}

                                            </div>
                                            <div class="action-btn bg-success  ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link"
                                                    data-link="{{ url('/' . $val->slug) }}" data-bs-toggle="tooltip"
                                                    data-bs-original-title="{{ __('Click to copy card link') }}"
                                                    onclick="copyToClipboard(this)"> <span class="text-white"> <i
                                                            class="ti ti-link text-white"></i></span></a>
                                            </div>
                                            @can('view analytics business')
                                                <div class="action-btn bg-secondary  ms-2">
                                                    <a href="{{ route('business.analytics', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Analytics') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-brand-google-analytics  text-white"></i></span></a>
                                                </div>
                                            @endcan
                                            @can('calendar appointment')
                                                <div class="action-btn bg-warning  ms-2">
                                                    <a href="{{ route('appointment.calendar', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Calender') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-calendar text-white"></i></span></a>
                                                </div>
                                            @endcan
                                            <div class="action-btn bg-dark  ms-2">
                                                <a href="#" data-size="md"
                                                    data-url="{{ route('business.qrcode', $val->id) }}"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                    title="{{ __('QR Code') }}" data-title="{{ __('Qr Code') }}"
                                                    class="btn btn-sm   "><i class="fa fa-qrcode text-white"></i></a>
                                            </div>
                                            @can('manage contact')
                                                <div class="action-btn bg-success  ms-2">
                                                    <a href="{{ route('business.contacts.show', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Contacts') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-phone text-white"></i></span></a>
                                                </div>
                                            @endcan
                                            <div class="action-btn bg-primary  ms-2">
                                                <a class="btn btn-sm  btn-icon ml-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    data-bs-original-title="{{ __('Preview') }}"
                                                    href="{{ url('/' . $val->slug) }}" target="-blank"><span
                                                        class="text-white"><i class="ti ti-eye"></i></span></a>
                                            </div>
                                            @if ($val->status != 'locked')
                                                <div class="action-btn bg-info  ms-2">
                                                    <a href="{{ route('business.edit', $val->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Business Edit') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-edit text-white"></i></span></a>
                                                </div>


                                                @can('delete business')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#"
                                                            class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $val->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><span class="text-white"><i
                                                                    class="ti ti-trash"></i></span></a>
                                                    </div>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['business.destroy', $val->id],
                                                        'id' => 'delete-form-' . $val->id,
                                                    ]) !!}
                                                    {!! Form::close() !!}
                                                @else
                                                    <span class="edit-icon align-middle bg-gray"><i
                                                            class="fas fa-lock text-white"></i></span>
                                                @endcan
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script type="text/javascript">
        function copyToClipboard(element) {
            var value = $(element).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('{{ __('Success') }}', '{{ __('Link Copy on Clipboard') }}', 'success');
        }
    </script>
@endpush
