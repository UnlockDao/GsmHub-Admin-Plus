<div class="user">
    <div class="photo">
        <img src="{{ url('jslogin\images\img-011.png') }}" />
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
                        <span class="sidebar-normal"> Info </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/logout') }}">
                        <span class="sidebar-mini"> O </span>
                        <span class="sidebar-normal"> Logout </span>
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
                <i class="material-icons">drag_indicator</i>
                <p>IMEI Service</p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{ url('supplier') }}">
                <i class="material-icons">account_balance</i>
                <p>Supplier</p>
            </a>
        </li>
    <li class="nav-item ">
        <a class="nav-link" href="{{ url('clientgroup') }}">
            <i class="material-icons">group</i>
            <p>User Group</p>
        </a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" href="{{ url('currencie') }}">
            <i class="material-icons">credit_card</i>
            <p>Currency</p>
        </a>
    </li>


</ul>