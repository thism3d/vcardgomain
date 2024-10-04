
<div class="card p-3">
    <div class="row">
        <div class="col-12">
            <div class="form-group col-md-12">
                {{Form::label('whatsapp',__('Whatsapp Number'))}}
                {{Form::text('whatsapp',null,array('class'=>'form-control','placeholder'=>__('Enter Your Whatsapp Number')))}}
                @error('whatsapp')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
                <div class="col-12">
                    <span id="whatsappMessage" class=" text-danger"></div>
                </div>
            </div>
        </div>
       
        <small class="text-muted">{{__('Note : Enter your WhatsApp number along with your country code (without the ‘+’ symbol). For instance, if your country code is +91 and your WhatsApp number is 7878787878, just type 917878787878.')}}</small>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Cancel')}}</button>
    <button type="button" class="btn btn-primary" id="shareWhatsapp">{{__('Share')}}</button>
</div>

<script>
    document.getElementById('shareWhatsapp').addEventListener('click', function() {
        var whatsappNumber = document.getElementById('whatsapp').value;
        if (whatsappNumber.trim() === '') {
            document.getElementById('whatsappMessage').innerText = '*Please enter a WhatsApp number.';
            return;
        }
        var slug = '{{ $business->slug }}';
        var url_link = `{{ url('/') }}/${slug}`;
        var text = encodeURIComponent(url_link);
        var url = 'https://api.whatsapp.com/send?phone=' + whatsappNumber + '&text=' + text;
        window.open(url, '_blank');
    });
</script>

