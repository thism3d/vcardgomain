@extends('layouts.admin')
@push('custom-scripts')
    <script>
        $(document).on('click', '.code', function() {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');

                // Clear the value of the auto-generated code field
                $('#auto-code').val('');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');

                // Clear the value of the manual code field
                $('#manual').val('');
            }
        });

        $(document).on('click', '#code-generate', function() {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('page-title')
    {{ __('Manage Coupon') }}
@endsection
@section('title')
    {{ __('Manage Coupon') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Coupon') }}</li>
@endsection
@section('action-btn')
    @can('create coupon')
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a class="btn btn-sm btn-primary btn-icon me-2" href="{{route('coupons.export')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Export">
                <i class="ti ti-file-export"></i>
            </a>
            @if (App\Models\Utility::getPaymentIsOn() && \Auth::user()->type == 'super admin')
                <a href="#" data-size="lg" data-url="{{ route('coupons.create') }}" data-ajax-popup="true"
                    data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Coupon') }}"
                    class="btn btn-sm btn-primary">
                    <i class="ti ti-plus"></i>
                </a>
            @endif
            
        </div>
    @endcan
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Code') }}</th>
                                    <th> {{ __('Discount') }}</th>
                                    <th> {{ __('Limit') }}</th>
                                    <th> {{ __('Used') }}</th>
                                    <th> {{ __('Expiry Date') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->name }}</td>
                                        <td>{{ $coupon->code }}</td>
                                        <td>{{ $coupon->discount }}</td>
                                        <td>{{ $coupon->limit }}</td>
                                        <td>{{ $coupon->used_coupon() }}</td>
                                        <td>{{ $coupon->expiry_date }}</td>
                                        {{-- <td>{{ $coupon->is_active }}</td> --}}
                                        <td>
                                            <span class="badge fix_badge 
                                                @if ($coupon->is_active == 0) bg-danger 
                                                @elseif ($coupon->is_active == 1) bg-info 
                                                @endif 
                                                p-2 px-3 rounded">
                                                {{ $coupon->is_active == 1 ? 'Active':'In Active' }}
                                            </span>
                                        </td>

                                        <div class="row ">
                                            <td class="d-flex">

                                                <div class="action-btn bg-success  ms-2">
                                                    <a href="{{ route('coupons.show', $coupon->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Show Coupon') }}"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-eye text-white"></i></span></a>
                                                </div>
                                                @can('edit coupon')
                                                    <div class="action-btn bg-info  ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-url="{{ route('coupons.edit', $coupon->id) }}" data-size="lg" 
                                                            data-ajax-popup="true" data-title="{{ __('Edit Coupon') }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Edit Coupon') }}"> <span
                                                                class="text-white"> <i
                                                                    class="ti ti-edit text-white"></i></span></a>
                                                    </div>
                                                @endcan
                                                @can('delete coupon')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#"
                                                            class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $coupon->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><span class="text-white"><i
                                                                    class="ti ti-trash"></i></span></a>
                                                    </div>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['coupons.destroy', $coupon->id],
                                                        'id' => 'delete-form-' . $coupon->id,
                                                    ]) !!}
                                                    {!! Form::close() !!}
                                                @endcan

                                            </td>

                                        </div>

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
