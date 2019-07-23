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
            <strong style="font-size: 16px;">
                <img style="padding-bottom: 1px;"
                     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAABG0lEQVQ4jc3ToW7UQRTF4e+QEYQgVlQQ0qCaGkwDphJWgiDBIhA8AuEJECgUug4PT9AggSB4gBWkooI0yNqD+S+BZbdpWgTj5t7M79w5ZyZVl11XLk3AUDtt56uNJNpKAtq/J05yKBYD+0me4/060LS28DjJDN/xEg9xisWY1H60/bRp3CS3kjyYtqf4gtttRaTtfttn5zbgT/iB+DhwlOTdRSA4goE5XrX9cIbiDPdwHSd4g/s4wNtxHrllSqsJLY1P25vY2xDh2oPLWpKv4nhgG49+i3Ot2obaCY5H290ke3h9xnW2k7xoewPfkjzB07a7kc9jos7a3l1VXXqRZKvt1al/re2d6e388mQH8wt6cigW+W9+8T+B/ARUKnYRg45uggAAAABJRU5ErkJggg=="
                     alt="ServerMonitor">

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
