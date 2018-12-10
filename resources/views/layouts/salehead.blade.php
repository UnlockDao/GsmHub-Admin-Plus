<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>S-Unlock</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Icons -->
    <link href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<style>

    .badge1 {
        position:relative;
    }
    .badge1[data-badge]:after {
        content:attr(data-badge);
        position:absolute;
        top:-10px;
        right:-10px;
        font-size:.7em;
        background:green;
        color:white;
        width:18px;height:18px;
        text-align:center;
        line-height:18px;
        border-radius:50%;
        box-shadow:0 0 1px #333;
    }
</style>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery.tableCheckbox.js') }}"></script>
<script>
    $('table').tableCheckbox({ /* options */ });
</script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css') }}">
<!-- Latest compiled and minified JavaScript -->
<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
</html>
