@php
$chatgpt_setting= App\Models\Utility::chatgpt_setting(\Auth::user()->creatorId());
@endphp
 
 {{ Form::model($coupon, ['route' => ['coupons.update', $coupon->id], 'method' => 'PUT']) }}
 @if(isset($chatgpt_setting['chatgpt_key']) && (!empty($chatgpt_setting['chatgpt_key'])))
 <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
     data-bs-placement="top">
     <a href="javascript:void(0)" data-size="lg" class="btn btn-sm btn-primary" data-ajax-popup-over="true"
         data-url="{{ route('generate', ['coupon']) }}" data-bs-toggle="tooltip" data-bs-placement="top"
         title="{{ __('Generate') }}" data-title="{{ __('Generate content with AI') }}">
         <i class="fas fa-robot"></i>&nbsp;{{ __('Generate with AI') }}
     </a>
 </div>
 @endif
 <div class="row">
     <div class="form-group col-md-12">
         {{ Form::label('name', __('Name'), ['class' => 'form-control-label']) }}
         {{ Form::text('name', null, ['class' => 'form-control font-style', 'required' => 'required' ,'placeholder'=>'Enter Coupon Name']) }}
     </div>
     <div class="form-group col-md-12">
        {{ Form::label('type', __('type'), ['class' => 'form-label']) }}
        {!! Form::select('type', $couponType, null, [
            'class' => 'form-control select2',
            'required' => 'required',
            'onchange' => 'updateLabelDicount()',
        ]) !!}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('Minimum spend', __('Minimum spend'), ['class' => 'form-label']) }}
        {{ Form::number('minimum_spend', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
    </div>

    <div class="form-group col-md-6">
        {{ Form::label('Maximum spend', __('Maximum spend'), ['class' => 'form-label']) }}
        {{ Form::number('maximum_spend', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
    </div>
  
    <div class="form-group col-md-6">
        {{ Form::label('discount', __('Discount'), ['class' => 'form-label discount-label']) }}
        {{ Form::label('amount', __('Amount'), ['class' => 'form-label amount-label d-none']) }}
        {{ Form::number('discount', null, ['class' => 'form-control discount-input', 'required' => 'required', 'step' => '0.01', 'placeholder' => 'Enter Discount']) }}
      </div>
     <div class="form-group col-md-6">
         {{ Form::label('limit', __('Limit'), ['class' => 'form-control-label']) }}
         {{ Form::number('limit', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>'Enter Limit']) }}
     </div>

    <div class="form-group col-md-6">
        {{ Form::label('coupon_limit_user', __('Usage limit per user'), ['class' => 'form-label']) }}
        {{ Form::number('per_user_limit', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('coupon_expiry_date', __('Expiry Date'), ['class' => 'form-label']) }}
        {{ Form::date('expiry_date', null, ['class' => 'form-control', 'required' => 'required','id'=>'coupon_expiry_date']) }}
    </div>
   
     <div class="form-group col-md-12">
         {{ Form::label('code', __('Code'), ['class' => 'form-control-label']) }}
         <div class="input-group">
         {{ Form::text('code', null, ['class' => 'form-control', 'required' => 'required','placeholder'=>"Coupon Code" ,'id'=>"auto-code"]) }}
         <button class="btn btn-outline-secondary " id="code-generate" type="button"><i
            class="fas fa-history pr-1"></i></button>
         </div>
     </div>
     <div class="form-group col-md-4">
        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
        <div class="form-check form-switch custom-switch-v1 float-end">
            <input type="checkbox" name="is_active"
                class="form-check-input input-primary is_active" value="{{$coupon->is_active}}"
                data-name="plan" {{$coupon->is_active=='1'?'checked':''}}>
            <label class="form-check-label" for="is_active"></label>
        </div>
    </div>
     

 </div>
 <div class="modal-footer p-0 pt-3">
     <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
     <input class="btn btn-primary" type="submit" value="{{ __('Edit') }}">
 </div>
 {{ Form::close() }}

 <script>
    function updateLabelDicount() {
        const selectedType = document.getElementById('type').value;
        const discountLabel = document.querySelector('.discount-label');
        const amountLabel = document.querySelector('.amount-label');
        const discountInput = document.querySelector('.discount-input');

        if (selectedType === 'flat') {
            discountLabel.classList.add('d-none');
            amountLabel.classList.remove('d-none');
            discountInput.placeholder = 'Enter Amount';
        } else {
            discountLabel.classList.remove('d-none');
            amountLabel.classList.add('d-none');
            discountInput.placeholder = 'Enter Discount (%)';
        }
    }

    // Call updateLabelDicount on page load
    updateLabelDicount();
</script>
