@extends('layouts.admin')
@php
    $category_path = \App\Models\Utility::get_file('category');
@endphp
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Business Category') }}</li>
@endsection
@section('page-title')
    {{ __('Business Category') }}
@endsection
@section('title')
    {{ __('Business Category') }}
@endsection
@section('action-btn')
    <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
        data-bs-placement="top">
        <a href="#" data-size="md" data-url="{{ route('category.create') }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" title="{{ __('Create') }}" data-title="{{ __('Create New Business Category') }}"
            class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-3">
            @include('layouts.marketplace_setup')
        </div>
        <div class="col-9 ">
            <div class="card">
                <div class="card-header">
                    <h5>{{__('Business Category')}}</h5>
                </div>
                <div class="card-body row">
                    @foreach ($categoryData as $key => $category)
                        <div class="col-xl-3">
                            <div class="card ">
                                <div class=" text-end">

                                    <div class="btn-group card-option">
                                        <button type="button" class="btn" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="feather icon-more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#" class="dropdown-item user-drop"
                                                data-url="{{ route('category.edit', $category->id) }}"
                                                data-ajax-popup="true" data-title="{{ __('Edit Business Category') }}"><i
                                                    class="ti ti-edit"></i><span
                                                    class="ml-2">{{ __('Edit') }}</span></a>



                                            <a href="#" class="bs-pass-para dropdown-item user-drop"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $category->id }}"
                                                title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top"><i class="ti ti-trash"></i><span
                                                    class="ml-2">{{ __('Delete') }}</span></a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['category.destroy', $category->id],
                                                'id' => 'delete-form-' . $category->id,
                                            ]) !!}
                                            {!! Form::close() !!}


                                        </div>
                                    </div>

                                </div>
                                <div class="card-body text-center pt-0 ">
                                    <div class="category-card-img">
                                        <?php
                                        $imagePath = $category_path . '/' . $category->logo;
                                        $headers = @get_headers($imagePath);
                                        ?>
                                        @if ($headers && strpos($headers[0], '200'))
                                            <img src="{{ !empty($category->logo) ? $category_path . '/' . $category->logo : asset('custom/img/placeholder-image21.jpg') }}"
                                                class="img_categorys_fix_size">
                                        @else
                                            <img class="rounded img2"
                                                src="{{ asset('custom/category/category' . $key + 1 . '.png') }}"
                                                alt="" width="100px" height="100px">
                                        @endif
                                    </div>
                                    <h4 class="mt-2 ">{{ ucFirst($category->name) }}</h4>
                                </div>



                            </div>


                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
