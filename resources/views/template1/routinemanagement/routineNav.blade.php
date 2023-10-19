<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover mr-auto">
                <li class="nav-item {{Request::is('class-routine/index*') ? 'active':''}}">
                    <a class="nav-link" href="{{ route('classroutine.index') }}" id="nav-hov">
                        View Class Routine
                    </a>
                </li>
                <li class="nav-item {{Request::is('class-routine/create*') ? 'active':''}}">
                    <a class="nav-link" href="{{ route('classroutine.create') }}" id="nav-hov">
                        Create Class Routine
                    </a>
                </li>
                {{-- unc_message --}}
                <li class="nav-item {{Request::is('routine/exam/*') ? 'active':''}}">
                    <a class="nav-link" href="{{ route('examroutine.index') }} " id="nav-hov">
                        Exam Routine
                    </a>
                </li>

                <li class="nav-item" id="routine_setting">
                    <a class="nav-link" href="#" id="nav-hov">
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Setting Submenu --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded" style="display: none;" id="setting_submenu">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ml-auto">
                <li class="nav-item " {{Request::is('routine/time-setting') ? 'active':''}}>
                    <a class="nav-link" href="{{ route('routine.time-setting.index') }}" id="nav-hov">
                        Time Setting
                    </a>
                </li>
                <li class="nav-item " {{Request::is('routine/time-setting') ? 'active':''}}>
                    <a class="nav-link" href="{{ route('routine.period.index') }}" id="nav-hov">
                        Period List
                    </a>
                </li>

                <li class="nav-item " {{Request::is('routine/set-design') ? 'active':''}}>
                    <a class="nav-link" href="{{ route('routine.set-design.index') }}" id="nav-hov">
                        Set Design
                    </a>
                </li>
                
            </ul>
        </div>
    </div>
</nav>
@push('js')
    <script>

$('#routine_setting').click(function(){
    $('#setting_submenu').show();
    
    
})
    </script>
@endpush