<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!-- LOGO -->
        <a href="{{ url('/') }}" class="logo text-center">
                        <span class="logo-lg">
                            <img src="{{asset('')}}/assets/images/logos.png" alt="" height="16">
                        </span>
            <span class="logo-sm">
                            <img src="{{asset('')}}/assets/images/logosm.png" alt="" height="16">
                        </span>
        </a>

        <!--- Sidemenu -->
        <ul class="metismenu side-nav">

            <li class="side-nav-title side-nav-item">App</li>

            <li class="side-nav-item">
                <a href="{{ url('/') }}" class="side-nav-link">
                    <i class="dripicons-meter"></i>
                    <span> Dashboards </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="dripicons-view-apps"></i>
                    <span> Services </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ url('imei') }}">IMEI Services</a>
                    </li>
                    <li>
                        <a href="{{ url('serverservice') }}">Server Services</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="dripicons-copy"></i>
                    <span> Orders </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ url('imeiorder') }}">IMEI Orders</a>
                    </li>
                    <li>
                        <a href="{{ url('serverorder') }}">Server Orders</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="dripicons-browser"></i>
                    <span> Reports </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    @if(CUtil::apAdmin())
                    <li>
                        <a href="{{ url('finance') }}">Finance Reports</a>
                    </li>
                    <li>
                        <a href="{{ url('profitreport') }}">Profit Reports</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ url('invoicereport') }}">Invoice Reports</a>
                    </li>
                    <li>
                        <a href="{{ url('check-transaction') }}">Check Transactions</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-title side-nav-item mt-1">Others</li>

            <li class="side-nav-item">
                <a href="{{ url('members') }}" class="side-nav-link">
                    <i class="dripicons-briefcase"></i>
                    <span> Members </span>
                </a>
            </li>
            @if(CUtil::apAdmin())
            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="dripicons-document"></i>
                    <span> Setting </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ url('clientgroup') }}">User Group</a>
                    </li>
                    <li>
                        <a href="{{ url('supplier') }}">Suppliers</a>
                    </li>
                    <li>
                        <a href="{{ url('currencie') }}">Currencies</a>
                    </li>
                    <li>
                        <a href="{{ url('role') }}">Roles</a>
                    </li>
                </ul>
            </li>
                @endif




        </ul>


        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
