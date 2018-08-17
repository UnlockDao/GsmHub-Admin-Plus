<div class="user">
    <div class="photo">
        <img src="{{ url('assets/avatar/avatar.jpg') }}" />
    </div>
    <div class="user-info">
        <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span>
                       Admin
                      <b class="caret"></b>
                    </span>
        </a>
        <div class="collapse" id="collapseExample">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span class="sidebar-mini"> T </span>
                        <span class="sidebar-normal"> Hồ sơ </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}">
                        <span class="sidebar-mini"> O </span>
                        <span class="sidebar-normal"> Đăng xuất </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<ul class="nav">
    <ul class="nav">
        <li class="nav-item ">
            <a class="nav-link" href="{{ url('imei') }}">
                <i class="material-icons">card_giftcard</i>
                <p>IMEI Service</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{ url('supplier') }}">
                <i class="material-icons">card_giftcard</i>
                <p>Supplier</p>
            </a>
        </li>
    <li class="nav-item ">
        <a class="nav-link" href="{{ url('chietkhau') }}">
            <i class="material-icons">card_giftcard</i>
            <p>User Group</p>
        </a>
    </li>


</ul>