@php
$card_theme = json_decode($business->card_theme);
@endphp

<div class=" row">
    {{-- @include('card.' . $card_theme->theme . '.index') --}}
    <iframe src="{{ url('/' . $business->slug) }}" width="100%" height="600" frameborder="0"></iframe>
</div>

