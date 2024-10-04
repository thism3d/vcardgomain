<li class="dash-item dash-hasmenu {{ (Request::route()->getName() == 'landingpage.home') ? ' active' : '' }}">
    <a href="{{ route('landingpage.home') }}" class="dash-link">
        <span class="dash-micon"><i class="ti ti-license"></i></span><span class="dash-mtext">{{__('Landing Page')}}</span>
    </a>
</li>
