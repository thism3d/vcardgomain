{{ Form::open(['url' => route('category.store'), 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-12">
        {{ Form::label('category_name', __('Category Name'), ['class' => 'form-label']) }}
        {!! Form::text('name',null, ['class' => 'form-control ', 'required' => 'required','placeholder'=>'Category Name']) !!}
        @error('platform')
            <small class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
        @enderror
    </div>
    <div class="col-12 form-group img-validate-class">
        {{ Form::label('category_image', __('Category Logo'), ['class' => 'form-label']) }}
        <div class="choose-files">
            <label for="cat_logo">
                <div class=" bg-primary company_logo_update" style="cursor: pointer;"> <i
                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>

                <input type="file" class="form-control file d-none file-validate" id="cat_logo" name="category_image"
                    data-filename="profiles"
                    onchange="document.getElementById('category_icon').src = window.URL.createObjectURL(this.files[0])">
            </label>
            <p class="file-error text-danger" style=""></p>
        </div>
        <div class="border col-md-4 mt-2">
            <img src="{{asset('custom/img/placeholder-image21.jpg')}}" id="category_icon" width="100%" />
        </div>
        {{-- <span class="profiles"></span> --}}
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input class="btn btn-primary" type="submit" value="{{ __('Create') }}">
</div>
{{ Form::close() }}



