
<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover mr-auto">
                <li class="nav-item  {{Request::is('teacherpanel/account-management/dashboard') ? 'active':''}}">
                    <a class="nav-link" href="{{route('teacherpanel.account-management.dashboard.index')}}" id="nav-hov">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item {{Request::is('teacherpanel/account-management/collection*') ? 'active':''}}">
                    <a class="nav-link " href="{{route('teacherpanel.account-management.collection.index')}}" id="nav-hov">
                        Collection
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
