@extends('layouts.admin')
@section('page-title')
    {{ __('Add New Modules') }}
@endsection
@section('title')
    {{ __('Add New Modules') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('module.index') }}">{{ __('Module') }}</a>
</li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Add New Modules') }}</li>
@endsection
@push('css-page')
<link rel="stylesheet" href="{{ asset('custom/libs/dropzonejs/dropzone.css') }}">
@endpush
@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-10 col-xxl-8">
            <div class="card">
                <div class="card-body">
                    <SECTION>
                        <DIV id="dropzone">
                            <FORM class="dropzone needsclick" id="demo-upload">
                                <DIV class="dz-message needsclick">
                                    {{ __('Drop files here or click to upload and install.')}}<BR>
                                </DIV>
                            </FORM>
                        </DIV>
                    </SECTION>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')

<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>

    <script>
        // Dropzone has been added as a global variable.
        Dropzone.autoDiscover = false;
        var dropzone = new Dropzone('#demo-upload', {
            thumbnailHeight: 120,
            thumbnailWidth: 120,
            maxFilesize: 500,
            acceptedFiles: '.zip',
            url: "{{ route('module.install') }}",
            success: function(file, response) {
                if (response.flag == 1)
                {
                    toastrs('Success', response.msg, 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('module.index') }}";
                    }, 1000);
                }
            }
        });
        dropzone.on('sending', function(file, xhr, formData) {
            formData.append('_token', "{{ csrf_token() }}");
        });
    </script>
@endpush
