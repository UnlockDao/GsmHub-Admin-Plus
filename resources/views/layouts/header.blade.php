<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://s-unlock.com/default/images/header/favicon/favicon.ico">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pricing | S-Unlock</title>
    <link href="{{ asset('assets/css/material-dashboard.min.css?v=2.0.1') }}" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'
          type='text/css'>
    <script src="{{ asset('excel/tableToExcel.js') }}"></script>
</head>
<style>
    /* for custom scrollbar for webkit browser*/

    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }
    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }
    .sticky {
        position: fixed;
        top: 0;
    }
    #myHeader {
        background: #fff;
        z-index: 10;
        margin-left: -20px;
        margin-right: 30px;

    }
</style>
<body class="sidebar-mini">
<div class="wrapper">
    <div class="sidebar" data-color="rose" data-background-color="black">
        <div class="logo">
            <a href="{{ url('') }}" class="simple-text logo-mini">
                SU
            </a>

            <a href="{{ url('') }}" class="simple-text logo-normal">
                S-Unlock
            </a>
        </div>
        <div class="sidebar-wrapper">
            @include('layouts.sidebar')
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute fixed-top">
            @include('layouts.navbar')
        </nav>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
<div class="beep" style="display: none;"></div>
</body>
<!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
<script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-material-design.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
<!--  Plugin for Date Time Picker and Full Calendar Plugin  -->
<script src="{{ asset('assets/js/plugins/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/material-dashboard.js?v=2.0.1') }}"></script>
<!-- Forms Validations Plugin -->
<script src="{{ asset('assets/js/plugins/jquery.validate.min.js') }}"></script>
<!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
<script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/    -->
<script src="{{ asset('assets/js/plugins/jquery.datatables.js') }}"></script>
<script type="text/javascript" src="{{ asset('source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('source/jquery.fancybox.css?v=2.1.5') }}" media="screen"/>
<script type="text/javascript">
    $(document).ready(function () {
        $(".fancybox").fancybox({
            type: 'iframe',
            'width':1300,
            afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
                parent.location.reload(true);
                $.notify({icon: "notifications", message: 'Change value'});
            }
        });
    });

</script>
<script type="text/javascript">
    $('.defaultcurrency').on('change', function () {
        $('.defaultcurrency').not(this).prop('checked', false);
    });
</script>

<script>
    window.onscroll = function() {myFunction()};

    var header = document.getElementById("myHeader");
    var sticky = header.offsetTop;

    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
</script>
</html>
