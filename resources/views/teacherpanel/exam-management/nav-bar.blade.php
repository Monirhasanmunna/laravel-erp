
<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover mr-auto">
                <li class="nav-item  {{Request::is('role-management/users*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('teacherpanel.exam-management.dashboard.index')}}" id="nav-hov">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item {{Request::is('role-management/roles*') ? 'active':''}}">
                    <a class="nav-link " href="{{route('teacherpanel.exam-management.marks-input.index')}}" id="nav-hov">
                        Marks Entry
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
