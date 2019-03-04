<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>S-Unlock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('')}}/assets/images/favicon.ico">
    <!-- third party css -->
    <link href="{{asset('')}}/assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
    <!-- third party css end -->
    <!-- App css -->
    <link href="{{asset('')}}/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('')}}/assets/css/app.min.css" rel="stylesheet" type="text/css"/>
    <!-- third party css -->
    <link href="{{asset('')}}/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="{{asset('')}}/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="sidebar-enable" data-keep-enlarged="true">

<!-- Begin page -->
<div class="wrapper">

@include('layouts.left-sidebar')

<!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            @include('layouts.topbar')
            @yield('content')

        </div>
        <!-- content -->

        @include('layouts.footer')

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->


</div>
<!-- END wrapper -->

@include('layouts.right-sidebar')
<script src="{{asset('')}}/assets/js/app.min.js"></script>

<!-- third party js -->
<script src="{{asset('')}}/assets/js/vendor/jquery.dataTables.js"></script>
<script src="{{asset('')}}/assets/js/vendor/dataTables.bootstrap4.js"></script>
<script src="{{asset('')}}/assets/js/vendor/dataTables.responsive.min.js"></script>
<script src="{{asset('')}}/assets/js/vendor/responsive.bootstrap4.min.js"></script>
<script src="{{asset('')}}/assets/js/vendor/dataTables.checkboxes.min.js"></script>

<!-- third party js ends -->

<!-- demo app -->
<script src="{{asset('')}}/assets/js/pages/demo.products.js"></script>
<!-- end demo js-->
<!-- fancybox-->
<script type="text/javascript" src="{{ asset('source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('source/jquery.fancybox.css?v=2.1.5') }}" media="screen"/>
<script src="{{ asset('js/tableHeadFixer.js') }}"></script>
<script src="{{ asset('js/tableToExcel.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".fancybox").fancybox({
            type: 'iframe',
            width: "100%",
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
</body>

</html>