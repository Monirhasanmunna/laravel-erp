<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 mt-2 py-1 mb-2 nested-menu shadow-sm rounded">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ml-auto">
                <li class="nav-item {{ Request::is('academic/classes*') ? 'custom_nav' : '' }}">
                    <a class="nav-link" href="{{route('classes.show',$id)}}" id="nav-hov">
                        Shifts
                    </a>
                </li>
                <li class="nav-item {{ Request::is('academic/class/section*') ? 'custom_nav' : '' }}">
                    <a class="nav-link" href="{{route('section.index',$id)}}" id="nav-hov">
                        Sections
                    </a>
                </li>
                <li class="nav-item {{Request::is('academic/class/category*')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('category.index',$id)}}" id="nav-hov">
                        Categories
                    </a>
                </li>

                <li class="nav-item {{ Request::is('academic/class/group*') ? 'custom_nav' : '' }}">
                    <a class="nav-link" href="{{route('group.index',$id)}}" id="nav-hov">
                        Groups
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('subject.list',$id)}}" id="nav-hov">
                        Subject
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
