{{Form::open(array('url'=>'users','method'=>'post'))}}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter ' . (Auth::user()->type === 'super admin' ? 'Company' : 'User') . ' Name'), 'required' => 'required']) }}
            @error('name')
                <small class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter ' . (Auth::user()->type === 'super admin' ? 'Company' : 'User') . ' Email'), 'required' => 'required']) }}
            @error('email')
                <small class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>
    
    <div class="col-md-6" id="password">
        <div class="form-group">
            {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter ' . (Auth::user()->type === 'super admin' ? 'Company' : 'User') . ' Password'), 'minlength' => '6','autocomplete'=>"off",'id'=>'passwordInput']) }}
            @error('password')
                <small class="invalid-password" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>
    @if(\Auth::user()->type != 'super admin')
        <div class="form-group col-md-6">
            {{ Form::label('role', __('User Role'),['class'=>'form-label']) }}
            {!! Form::select('role', $roles, null,array('class' => 'form-control select2','required'=>'required')) !!}
            @error('role')
            <small class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
    @endif
   
</div>
<div class="col-md-6 mb-3 form-group">
    <label for="is_login">{{__('Login is enable')}}</label>
    <div class="form-check form-switch custom-switch-v1 float-end">
        <input type="checkbox" name="is_login" class="form-check-input input-primary pointer" id="is_login">
        <label class="form-check-label" for="is_login"></label>
    </div>
</div>
<div class="modal-footer p-0 pt-3">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input class="btn btn-primary" type="submit" value="{{ __('Create') }}">
</div>
{{Form::close()}}
<script>
    $(document).ready(function(){
        // Initially hide the password field
        $('#password').hide();
        
        // Check the status of the checkbox when it's clicked
        $('#is_login').click(function(){
            if($(this).is(':checked')){
                // If checkbox is checked, show the password field
                $('#password').show();
                $('#passwordInput').prop('required', true);
            } else {
                // If checkbox is unchecked, hide the password field
                $('#password').hide();
                $('#passwordInput').prop('required', false);
            }
        });
    });
</script>
