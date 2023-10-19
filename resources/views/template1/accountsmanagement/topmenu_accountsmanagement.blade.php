<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover mr-auto">

                <li class="nav-item {{Request::is('accountsmanagement/dashboard') ? 'active':''}}">
                    <a class="nav-link" href="{{route('accountsmanagement.dashboard')}}" id="nav-hov">
                        Dashboard
                    </a>
                </li>


                <li class="nav-item {{Request::is('accountsmanagement/payment/*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('payment.index')}}" id="nav-hov">
                        Collection
                    </a>
                </li>

                <li class="nav-item {{Request::is('accountsmanagement/student-payslip/*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('student-payslip.index')}}" id="nav-hov">
                        Payslip
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/expense/*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('expense.index')}}" id="nav-hov">
                        Expense
                    </a>
                </li>
                <li class="nav-item " id="reports">
                    <a class="nav-link" href="#" id="nav-hov">
                        Reports
                    </a>
                </li>
                <li class="nav-item " id="accounts_setting">
                    <a class="nav-link" href="#" id="nav-hov">
                        Settings
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>



<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded d-none" id="reports-nav">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ml-auto">
                <li class="nav-item {{Request::is('accountsmanagement/reports/expense') ? 'active':''}}">
                    <a class="nav-link" href="{{route('reports.expense-report')}}" id="nav-hov">
                        Expense Report
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/payment-report/paid') ? 'active':''}}">
                    <a class="nav-link" href="{{route('payment-report.index')}}" id="nav-hov">
                        Paid Report
                    </a>
                </li>

                <li class="nav-item {{Request::is('accountsmanagement/payment-report/unpaid') ? 'active':''}}">
                    <a class="nav-link" href="{{route('payment-report.unpaid')}}" id="nav-hov">
                        Unpaid Report
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/payment-report/unpaid') ? 'active':''}}">
                    <a class="nav-link" href="{{route('reports.invoice-date-to-date')}}" id="nav-hov">
                        Invoice Report
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/reports/yearly-payment-report') ? 'active':''}}">
                    <a class="nav-link" href="{{route('reports.yearly-report')}}" id="nav-hov">
                        Yearly Report
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/reports/monthly-payment-report') ? 'active':''}}">
                    <a class="nav-link" href="{{route('reports.monthly-report')}}" id="nav-hov">
                        Monthly Report
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Settings Sub Menu --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white z-index-3 py-1 mb-2 nested-menu shadow-sm rounded d-none" id="settings-nav">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ml-auto">
                <li class="nav-item {{Request::is('accountsmanagement/feestype*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('feestype.index')}}" id="nav-hov">
                        Fees Type
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/fees-setup*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('fees-setup.index')}}" id="nav-hov">
                        Bulk Fees Setup
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/student*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('accountsmanagement.index')}}" id="nav-hov">
                        Student Wise Fees
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/teacher-assign*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('accounts.teacher-assign.create')}}" id="nav-hov">
                        Teacher Assign
                    </a>
                </li>

                <li class="nav-item {{Request::is('accountsmanagement/scholarship*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('scholarship.index')}}" id="nav-hov">
                        Scholarship
                    </a>
                </li>
                <li class="nav-item {{Request::is('accountsmanagement/account*') ? 'active':''}}">
                    <a class="nav-link" href="{{route('account.index')}}" id="nav-hov">
                        Account
                    </a>
                </li>
                <li class="nav-item {{Request::is('payment-gateway/index') ? 'active':''}}">
                    <a class="nav-link" href="{{route('payment-gateway.index')}}" id="nav-hov">
                        Payment Gateway
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{--  Reports Menu   --}}



@push('js')
<script>
$('#accounts_setting').click(function(){
    $('#settings-nav').removeClass('d-none');
    $('#reports-nav').addClass('d-none');
});

$('#reports').click(function(){
    $('#settings-nav').addClass('d-none');
    $('#reports-nav').removeClass('d-none');
});
</script>

@endpush
