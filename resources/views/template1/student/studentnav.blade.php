<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded" id="student-nav">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover">
                <li class="nav-item {{Request::is('student/dashboard*')?'custom_nav':''}}">
                    <a class="nav-link" href="{{ route('student.dashboard') }}" id="nav-hov">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/list*')?'custom_nav':''}}">
                    <a class="nav-link" href="{{ route('student.list') }}" id="nav-hov">
                        Student List
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/admission*')?'custom_nav':''}}" id='admission'>
                    <a class="nav-link" href="{{ route('admission.index') }}" id="nav-hov">
                        Admission
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/migration/index')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('student.migration.index')}}" id="nav-hov">
                        Migration
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/subject-assign*')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('student.subject-assign.index')}}" id="nav-hov">
                        Subject Assign
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/subject-unassigned*')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('student.subject-unassigned.index')}}" id="nav-hov">
                        Subject Unassigned
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/profile-update')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('studentprofile.index')}}" id="nav-hov">
                        Update Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('student.report.index')}}" id="nav-hov">
                        Reports
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/birthday-wish/index')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('student.birthday-wish.index')}}" id="nav-hov">
                        Birthday Wish
                    </a>
                </li>
                <li class="nav-item {{Request::is('student/branch-migration*')?'custom_nav':''}}">
                    <a class="nav-link" href="{{route('student.branch-migration.index')}}" id="nav-hov">
                        Branch Migration
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
