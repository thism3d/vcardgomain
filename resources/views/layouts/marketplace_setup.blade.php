<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{ route('category.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('category*') ? 'active' : '')}}">{{__('Category Settings')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
        <a href="{{route('business.setup')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('business_setup*') ? 'active' : '')}}">{{__('Business Settings')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    </div>
</div>
