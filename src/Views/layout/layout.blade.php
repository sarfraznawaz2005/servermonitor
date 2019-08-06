<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    <title>ServerMonitor</title>

    <link rel="stylesheet" href="{{ asset('vendor/servermonitor/assets/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/servermonitor/assets/loading/jquery.loading.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/servermonitor/assets/servermonitor.css') }}">
</head>

<body>

<main role="main">

    <div class="card ">
        <div class="card-header bg-primary text-white stripe">
            <strong class="h5">
                @yield('title')
            </strong>

            <div class="float-right">
                @yield('header')
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="card-body">
            @yield('content')
        </div>

    </div>
</main>

<script src="{{ asset('vendor/servermonitor/assets/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/servermonitor/assets/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('vendor/servermonitor/assets/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/servermonitor/assets/sweetalert.min.js') }}"></script>
<script src="{{ asset('vendor/servermonitor/assets/loading/jquery.loading.min.js') }}"></script>

<script>
    window.ServerMonitorRefreshUrl = "{{route('servermonitor_refresh')}}";
    window.ServerMonitorRefreshAllUrl = "{{route('servermonitor_refresh_all')}}";
</script>

<script src="{{ asset('vendor/servermonitor/assets/servermonitor.js') }}"></script>

</body>
</html>
