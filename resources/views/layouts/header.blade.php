<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kế Toán</title>

    <link href="{{ asset('assets/css/material-dashboard.min.css?v=2.0.1') }}" rel="stylesheet"/>
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300|Material+Icons' rel='stylesheet'
          type='text/css'>
    <script src="{{ asset('excel/tableToExcel.js') }}"></script>
</head>
<body class="">
<div class="wrapper">
    <div class="sidebar" data-color="rose" data-background-color="black"
         data-image="{{ asset('assets/img/sidebar-1.jpg') }}">
        <div class="logo">
            <a href="{{ url('bak') }}" class="simple-text logo-mini">
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
        <footer class="footer">
            @include('layouts.footer')
        </footer>
    </div>
</div>
<div class="beep" style="display: none;"></div>
</body>
<!--   Core JS Files   -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
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
<link rel="stylesheet" type="text/css" href="{{ asset('source/jquery.fancybox.css?v=2.1.5') }}" media="screen" />
<script type="text/javascript">
    $(document).ready(function() {
        $('body').addClass('sidebar-mini');
        md.misc.sidebar_mini_active = true;

        $(".fancybox").fancybox({
            type: 'iframe',
            afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
                // parent.location.reload(true);
            }
        });
    });

</script>
</html>
