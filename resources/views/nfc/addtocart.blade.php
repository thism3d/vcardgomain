@php
    $admin_payment_setting = Utility::getAdminPaymentSetting();
@endphp
{{ Form::open(['url' => route('nfc.order.store', $nfcCard->id), 'enctype' => 'multipart/form-data']) }}
<div class="row">
    <div class="form-group col-12">
        {{ Form::label('business', __('Business'), ['class' => 'form-label']) }}
        {!! Form::select('business', $businessList, null, ['class' => 'form-control select2 businessList', 'required' => 'required']) !!}
        @error('platform')
            <small class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
        @enderror
    </div>

    <div class="col-6 form-group">
        <div class="row">
            {{ Form::label('Quantity', __('Quantity'), ['class' => 'form-label']) }}
            <div class="nfc-order-counter">
                <input id="minus" type="button" value="-" class="btn btn-sm btn-primary">
                <input id="quantity" type="text" value="1" min="1" name="quantity"
                    class="btn btn-sm btn-primary">
                <input id="plus" type="button" value="+" class="btn btn-sm btn-primary">

            </div>
        </div>
    </div>


    <div class="col-6 form-group">
        <div class="row nfc-card-price">
            {{ Form::label('price', __('Price'), ['class' => 'form-label']) }}
            <div class="price-best-price">
                <strong
                    id="skuBestPrice">{{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }}{{ $nfcCard->price }}</strong>
                    <span class="small"> {{__('(Default price for 1 quantity)')}}</span>
            </div>
            <span id="total-price">{{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }} 0,00</span>
            <input id="total-price-hidden" type="hidden" value="{{ $nfcCard->price }}" name="totalprice">

        </div>

        @error('price')
            <span class="invalid-favicon text-xs text-danger" role="alert">{{ $message }}</span>
        @enderror
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input class="btn btn-primary" type="submit" value="{{ __('Order') }}">
</div>
{{ Form::close() }}

<script>
    $('#plus').click(function add() {
        var $qtde = $("#quantity");
        var a = $qtde.val();

        a++;
        $("#minus").attr("disabled", !a);
        $qtde.val(a);
    });
    $("#minus").attr("disabled", !$("#quantity").val());

    $('#minus').click(function minusButton() {
        var $qtde = $("#quantity");
        var b = $qtde.val();
        if (b > 1) {
            b--;
            $qtde.val(b);
        } else {
            $("#minus").attr("disabled", true);
        }
    });

    /* On change */
    $(document).ready(function() {
        function updatePrice() {
            var priceProduct = '{{ $nfcCard->price }}';
            var price = parseFloat($("#quantity").val());
            var total = ((priceProduct) * (price)).toFixed(2);
            var currency='{{ !empty($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$' }}';

            $("#total-price").text(currency+total);
            $("#total-price-hidden").val(total);
        }
        // On the click/keyup/change
        $(document).ready(function() {
            updatePrice();
        });
        //$(document).on("load", "input", updatePrice);
        $(document).on("click", "input", updatePrice);
        $(document).on("keyup", "input", updatePrice);
        $(document).on("change", "input", updatePrice);

        $('#quantity').on('change keyup focus', function() {
            var removeLetters = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(removeLetters);
        });

    });
</script>

