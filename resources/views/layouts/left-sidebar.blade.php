<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!-- LOGO -->
        <a href="#" class="logo text-center">
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
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="dripicons-meter"></i>
                    <span class="badge badge-success float-right">2</span>
                    <span> Dashboards </span>
                </a>
                <ul class="side-nav-second-level collapse" aria-expanded="false">
                    @if(CUtil::apAdmin())
                    <li>
                        <a href="{{ url('home') }}">Revenue</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ url('orderdashboard') }}">Order</a>
                    </li>
                </ul>
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
                        <a href="{{ url('serverservice') }}">Server Service</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-item">
                <a href="javascript: void(0);" class="side-nav-link">
                    <i class="dripicons-copy"></i>
                    <span> Order </span>
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
                    <span> Report </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    @if(CUtil::apAdmin())
                    <li>
                        <a href="{{ url('profitreport') }}">Profit Report</a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ url('invoicereport') }}">Invoice Report</a>
                    </li>
                    <li>
                        <a href="{{ url('check-transaction') }}">Check Transaction</a>
                    </li>
                </ul>
            </li>

            <li class="side-nav-title side-nav-item mt-1">Other</li>

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
                        <a href="{{ url('clientgroup') }}">Clientgroup</a>
                    </li>
                    <li>
                        <a href="{{ url('supplier') }}">Supplier</a>
                    </li>
                    <li>
                        <a href="{{ url('currencie') }}">Currencie</a>
                    </li>
                    <li>
                        <a href="{{ url('role') }}">Role</a>
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
