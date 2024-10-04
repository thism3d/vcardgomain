@php
    $nfcImage = \App\Models\Utility::get_file('nfc/card_image');

    $users = \Auth::user();
@endphp
{{ Form::model($nFCCardData, ['route' => ['nfc.update', $nFCCardData->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="row">

    <div class="col-12 form-group">
        {{ Form::label('NFC Card Name', __('NFC Card Name'), ['class' => 'form-control-label']) }}
        {{ Form::text('nfc_card_name', $nFCCardData->card_name, ['class' => 'form-control mt-2', 'placeholder' => __('Enter NFC Card Name')]) }}
        @error('nfc_card_name')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-12 form-group">
        {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}
        {{ Form::number('price', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Price'), 'min' => '1', 'step' => '0.01']) }}
        @error('price')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-12 form-group img-validate-class">
        {{ Form::label('card_images', __('Card Image'), ['class' => 'form-label']) }}
        <div class="choose-files">
            <label for="avatar">
                <div class=" bg-primary company_logo_update" style="cursor: pointer;"> <i
                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>

                <input type="file" class="form-control file d-none file-validate" id="avatar" name="nfc_image"
                    data-filename="profiles"
                    onchange="document.getElementById('nfc_image').src = window.URL.createObjectURL(this.files[0])">
            </label>
            <p class="file-error text-danger" style=""></p>
        </div>
        <div class="border col-md-4 mt-2">
            <?php
            $imagePath = $nfcImage . '/' . $nFCCardData->image;
            
            $headers = @get_headers($imagePath);
            
            ?>
            @if ($headers && strpos($headers[0], '200'))
                <img src="{{ isset($nFCCardData->image) && !empty($nFCCardData->image) ? $nfcImage . '/' . $nFCCardData->image : asset('custom/img/logo-placeholder-image-21.png') }}"
                    id="nfc_image" width="100%" />
            @else
                <img style="width: 300px;height: 200px;" class="rounded"
                    src="{{ asset('custom/nfcimg/nfc' . $nFCCardData->id . '.png') }}" alt="">
            @endif
        </div>
        {{-- <span class="profiles"></span> --}}
    </div>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input class="btn btn-primary" type="submit" value="{{ __('Update') }}">
</div>
{{ Form::close() }}
