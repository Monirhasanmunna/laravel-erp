<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover mr-auto">

                <li class="nav-item {{Request::is('sms/dashboard') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.dashboard')}}" id="nav-hov">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/portal') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.portal')}}" id="nav-hov">
                        Send SMS
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/student-sms') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.student-sms.index')}}" id="nav-hov">
                        Student SMS
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/result-sms') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.result-sms.index')}}" id="nav-hov">
                        Results SMS
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/contact*') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.contact.index')}}" id="nav-hov">
                        Manual SMS
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/absent-sms/student*') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.absent-sms.student.index')}}" id="nav-hov">
                        Absent SMS
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/template') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.template.index')}}" id="nav-hov">
                        Add Template
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/orders') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.orders.index')}}" id="nav-hov">
                        Orders
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/sms-report') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.sms-report.index')}}" id="nav-hov">
                        SMS Reports
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/date-to-date-report') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.date-to-date-report.index')}}" id="nav-hov">
                         History
                    </a>
                </li>
                <li class="nav-item {{Request::is('sms/sms-notification') ? 'active':''}}">
                    <a class="nav-link" href="{{Route('sms.sms-notification.index')}}" id="nav-hov">
                        Notifications
                    </a>
                </li>
            
            </ul>
        </div>
    </div>
</nav>

