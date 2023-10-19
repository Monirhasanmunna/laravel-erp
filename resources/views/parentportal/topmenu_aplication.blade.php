<ul class="navbar-nav navbar-nav-hover mr-auto">
    <li class="nav-item  {{Request::is('parentportal/aplication') ? 'active':''}}">
        <a class="nav-link" href="{{ route('studentleaveapplication.index') }}" id="nav-hov">
            Leave Application
        </a>
    </li>
   
    <li class="nav-item  {{Request::is('parentportal/aplication') ? 'active':''}}">
        <a class="nav-link" href="{{ route('aplication.index') }}" id="nav-hov">
            Feed Back
        </a>
    </li>
</ul>