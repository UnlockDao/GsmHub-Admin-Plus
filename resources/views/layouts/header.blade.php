<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pricing - S Unlock</title>
    <!-- Favicon -->
    <link href="{{ asset('assets/img/brand/favicon.png" rel="icon" type="image/png') }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('assets/css/argon.css?v=1.0.0') }}" rel="stylesheet">
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    /* for custom scrollbar for webkit browser*/

    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 75, 180, 0.3);
    }
    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }
    #parent {
        height: 65vh;
    }
    .table th{
        border-top: 0px;
        z-index: 999;
    }
</style>
<style>
    .badge1 {
        position: relative;
    }

    .badge1[data-badge]:after {
        content: attr(data-badge);
        position: absolute;
        top: -10px;
        right: -10px;
        font-size: .7em;
        background: green;
        color: white;
        width: 18px;
        height: 18px;
        text-align: center;
        line-height: 18px;
        border-radius: 50%;
        box-shadow: 0 0 1px #333;
    }
</style>
<body>
<!-- Sidenav -->
@include('layouts.sidebar')
<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
@include('layouts.navbar')
    <!-- Header -->

    <!-- Page content -->
    @yield('content')
<!-- Footer -->

    <footer class="footer">
        <div class="row align-items-center justify-content-xl-between">
            <div class="col-xl-6">
                <div class="copyright text-center text-xl-left text-muted">
                    &copy; 2018 <a href="" class="font-weight-bold ml-1" target="_blank">S-Developers</a>
                </div>
            </div>
            <div class="col-xl-6">
                <ul class="nav nav-footer justify-content-center justify-content-xl-end">
                    <li class="nav-item">
                        <a href="#" class="nav-link" target="_blank">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a href="https://s-unlock.com/" class="nav-link" target="_blank">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" target="_blank">MIT License</a>
                    </li>
                </ul>
            </div>
        </div>

    </footer>

</div>
</div>
<!-- Argon Scripts -->
<!-- Core -->
<script src="{{ asset('js/tableHeadFixer.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Optional JS -->
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<!-- Argon JS -->
<script src="{{ asset('assets/js/argon.js?v=1.0.0') }}"></script>
<script src="{{ asset('js/tableToExcel.js') }}"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script type="text/javascript" src="{{ asset('source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('source/jquery.fancybox.css?v=2.1.5') }}" media="screen"/>
<script type="text/javascript">
    $(document).ready(function () {
        $(".fancybox").fancybox({
            type: 'iframe',
            width: "100%",
            afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
                parent.location.reload(true);
                $.notify({icon: "notifications", message: 'Change value'});
            }
        });
    });

</script>
<script type="text/javascript">
    $(function() {
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'Y/MM/DD H:mm:s',
                cancelLabel: 'Clear'
            }
        });
        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('Y/MM/DD H:mm:s') + ' - ' + picker.endDate.format('Y/MM/DD H:mm:s'));
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $('input[name="datefilterh"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'Y/MM/DD H:mm:s',
                cancelLabel: 'Clear'
            }
        });
        $('input[name="datefilterh"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('Y/MM/DD H:mm:s') + ' - ' + picker.endDate.format('Y/MM/DD H:mm:s'));
            $('#formId').submit(); // <-- SUBMIT
        });

        $('input[name="datefilterh"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
<script type="text/javascript">
    $('.defaultcurrency').on('change', function () {
        $('.defaultcurrency').not(this).prop('checked', false);
    });
</script>
<script>
    $(document).ready(function() {
        $("#fixTable").tableHeadFixer();
    });
</script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

</body>

</html>