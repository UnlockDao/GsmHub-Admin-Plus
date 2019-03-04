<!-- Topbar Start -->
<div class="navbar-custom">
    <ul class="list-unstyled topbar-right-menu float-right mb-0">
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
               aria-expanded="false">
                                    <span class="account-user-avatar">
                                        <img src="{{asset('')}}/assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">
                                    </span>
                <span>
                                        <span class="account-user-name">{{Auth::user()->user_name}}</span>
                                        <span class="account-position">{{Auth::user()->user_access}}</span>
                                    </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome !</h6>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle mr-1"></i>
                    <span>Tài khoản</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-edit mr-1"></i>
                    <span>Cài đặt</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-lifebuoy mr-1"></i>
                    <span>Hỗ trợ</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-lock-outline mr-1"></i>
                    <span>Khóa</span>
                </a>

                <!-- item-->
                <a href="{{ url('/logout') }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-logout mr-1"></i>
                    <span>Đăng xuất</span>
                </a>

            </div>
        </li>

    </ul>
    <button class="button-menu-mobile open-left disable-btn">
        <i class="mdi mdi-menu"></i>
    </button>
    <div class="app-search">
        <form id="formId">
            <div class="input-group">
                <input type="text" class="form-control" name="datefilterh" autocomplete="off" value="">
                <span class="mdi mdi-magnify"></span>
            </div>
        </form>
    </div>
</div>
<!-- end Topbar -->