
<style>
    .menu-title{
        color:#000000!important;
    }
    .sidebar .nav .nav-item .menu-icon {
        width:25px!important;
        height:25px!important;
    }
    .sidebar .nav .nav-item .nav-link {
            height:42px!important;
    }
</style>

<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="{{ route('home') }}"><img src="{{ asset('assets/images/Edteco_logo.png') }}"
                alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img
                src="{{ asset('assets/images/third-degree.svg') }}" alt="logo" /></a>
    </div>
    <ul class="nav" id="nav">
        @foreach ($modules as $module)
            <li class="nav-item menu-items {{Request::is($module->is_request."*") ? 'active' : ''}}">
                <a class="nav-link" href="{{ route($module->route) }}">
                    <span class="menu-icon">
                        <i class="{{$module->icon_class}}"></i>
                    </span>
                    <span class="menu-title">{{$module->name}}</span>
                </a>
            </li>
        @endforeach
        <hr>
        <li class="nav-item menu-items mb-5">
            <div style="margin-left:18px">
                <i class="fa-solid fa-copyright"></i>
                <span class="menu-title">Teamx Technologies</span>
            </div>

        </li>

    </ul>
</nav>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
    $(document).ready(function(){


      
    });
</script>