<style>
    .middle {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        text-align: center;
    }

    .contact-inner {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: #f1f1f1;
        border-radius: 30%;
        box-shadow: 0 5px 15px -5px #00000070;
        color: #3498db;
        overflow: hidden;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .contact-inner img {
        line-height: 40px;
        font-size: 18px;
        width: 22px;
        height: 22px;
        transition: 0.2s linear;
    }

    .contact-inner:hover img {
        transform: scale(1.3);
        color: #f1f1f1;
    }

    .contact-inner::before {
        content: "";
        position: absolute;
        width: 120%;
        height: 120%;
        background: var(--color-customColor);
        transform: rotate(45deg);
        left: -110%;
        top: 90%;
    }

    .contact-inner:hover::before {
        animation: aaa 0.7s 1;
        top: -10%;
        left: -10%;
    }

    @keyframes aaa {
        0% {
            left: -110%;
            top: 90%;
        }

        50% {
            left: 10%;
            top: -30%;
        }

        100% {
            top: -10%;
            left: -10%;
        }
    }

    .contact-list ul {
        list-style: none;
    }

    .contact-inner-img {
        border: 1px solid #ced4da;
        border-top: 0;
        border-left: 0;
        border-bottom: 0;
    }
    .contact-modal ul.list-none {
        padding-left: 10px;
    }

</style>
<div class="contact-modal d-flex flex-column gap-2">
    @if (!is_null($contactinfo_content) && !is_null($contactDetail) && !empty($contactinfo_content))
        @foreach ($contactinfo_content as $key => $val)
            @foreach ($val as $key1 => $val1)
                @if ($key1 == 'Phone')
                    @php $href = 'tel:'.$val1; @endphp
                @elseif($key1 == 'Email')
                    @php $href = 'mailto:'.$val1; @endphp
                @elseif($key1 == 'Address')
                    @php $href = ''; @endphp
                @else
                    @php $href = $val1 @endphp
                @endif
                @if ($key1 != 'id')
                    <div class="d-flex m-2 contact-list">
                        <div class="contact-inner-img px-3">

                            @if ($key1 == 'Address')
                                @foreach ($val1 as $key2 => $val2)
                                    @if ($key2 == 'Address_url')
                                        @php $href = $val2; @endphp
                                    @endif
                                @endforeach

                                <a class="contact-inner" href="{{ $href }}">
                                    <img src="{{ asset('custom/theme1/icon/social/' . strtolower($key1) . '.svg') }}"
                                        class="img-fluid">
                                </a>
                            @else
                                <a class="contact-inner" href="{{ $val1 }}">
                                    <img src="{{ asset('custom/theme1/icon/social/' . strtolower($key1) . '.svg') }}"
                                        class="img-fluid">
                                </a>
                            @endif

                        </div>
                        <div class="contact-inner-content">
                            <ul class="list-none mb-0">
                                @if ($key1 == 'Address')
                                    @foreach ($val1 as $key2 => $val2)
                                        @if ($key2 == 'Address_url')
                                            @php $href = $val2; @endphp
                                        @endif
                                    @endforeach
                                    <a href="{{ $href }}" class="text-black">
                                        @foreach ($val1 as $key2 => $val2)
                                            @if ($key2 == 'Address')
                                                <li>{{ $key2 }}</li>
                                                <li>{{ $val2 }}</li>
                                            @endif
                                        @endforeach
                                    </a>
                                @else
                                    @if ($key1 == 'Whatsapp')
                                        <a href="{{ url('https://wa.me/' . $href) }}" class="text-black">
                                            <li>{{ $key1 }}</li>
                                            <li>{{ $val1 }}</li>
                                        </a>
                                    @else
                                        <a href="{{ $href }}" class="text-black">
                                            <li>{{ $key1 == 'Web_url' ? 'Web URL' : $key1 }}</li>
                                            <li>{{ $val1 }}</li>
                                        </a>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
        @else
        <div class="col-12 text-center py-4">
            <div class="alert alert-warning" role="alert">
                <h6 class="alert-heading">{{ __('No Contact Details Available') }}</h6>
                <p>{{ __('It looks like we don\'t have any contact information for this business yet.') }}</p>
            </div>
        </div>
    @endif
</div>
<script> 

</script>
