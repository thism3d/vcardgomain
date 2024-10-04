@extends('layouts.admin')
@section('page-title')
    {{ __('Custom Domain Request') }}
@endsection
@section('title')
    {{ __('Custom Domain Request') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Custom Domain Request') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class=" card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Company Name') }}</th>
                                    <th>{{ __('Business Name') }}</th>
                                    <th>{{ __('Domain') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Created On') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($domain_request->count() > 0)
                                    @foreach ($domain_request as $prequest)
                                        <tr>
                                        <tr>
                                            <td>
                                                <div class="font-style font-weight-bold">{{ $prequest->user->name }}</div>
                                            </td>
                                            <td>
                                                <div class="font-style font-weight-bold">{{ $prequest->business->title }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-style font-weight-bold">{{ $prequest->domain_name }}</div>
                                            </td>
                                            <td>
                                                @if ($prequest->status == 0)
                                                    <span
                                                        class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __(App\Models\DomainRequest::$statues[$prequest->status]) }}</span>
                                                @elseif($prequest->status == 1)
                                                    <span
                                                        class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(App\Models\DomainRequest::$statues[$prequest->status]) }}</span>
                                                @elseif($prequest->status == 2)
                                                    <span
                                                        class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __(App\Models\DomainRequest::$statues[$prequest->status]) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ \App\Models\Utility::getDateFormated($prequest->created_at, true) }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if ($prequest->status == 0)
                                                        <a href="{{ route('domain_request.request', [$prequest->id, 1]) }}"
                                                            class="btn btn-sm btn-icon  btn-primary me-2">
                                                            <i class="ti ti-check "></i>
                                                        </a>
                                                        <a href="{{ route('domain_request.request', [$prequest->id, 0]) }}"
                                                            class="btn btn-sm btn-icon  btn-danger me-2">
                                                            <i class="ti ti-x "></i>
                                                        </a>
                                                    @endif
                                                    <a href="#"
                                                        class="bs-pass-para btn btn-sm btn-icon  btn-danger me-2"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $prequest->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><span class="text-white"><i
                                                                class="ti ti-trash"></i></span></a>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['domain_request.destroy', $prequest->id],
                                                        'id' => 'delete-form-' . $prequest->id,
                                                    ]) !!}
                                                    {!! Form::close() !!}
                                                </div>
                                            </td>
                                        </tr>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th scope="col" colspan="7">
                                            <h6 class="text-center p-4">{{ __('No Manually Domain Request Found.') }}</h6>
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
