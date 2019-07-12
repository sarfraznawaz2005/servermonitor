<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    <title>ServerMonitor</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
          crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap4.min.css">

    <style>
        body {
            font-family: sans-serif;
            font-size: 0.8rem;
            line-height: 1.0;
            background-color: #cccccc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3E%3Cg fill='%23dddddd' fill-opacity='0.4'%3E%3Cpath fill-rule='evenodd' d='M0 0h4v4H0V0zm4 4h4v4H4V4z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .card-body {
            background-color: #cccccc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3E%3Cg fill='%23dddddd' fill-opacity='0.4'%3E%3Cpath fill-rule='evenodd' d='M0 0h4v4H0V0zm4 4h4v4H4V4z'/%3E%3C/g%3E%3C/svg%3E");
            padding: 0.50rem;
        }

        .card-header {
            padding: 10px !important;
        }

        .table td, .table th {
            padding: .50rem;
            vertical-align: middle;
        }

        .table td {
            background: #fff;
        }

        .table thead {
            background-image: linear-gradient(#eee, #ddd);
        }

        .card {
            border: none;
        }

        .card-header {
            padding: .40rem 1.25rem;
            line-height: 250%;
        }

        .stripe {
            color: white;
            background: repeating-linear-gradient(45deg, #007BFF, #007BFF 20%, #3898ff 10px, #3898ff);
            background-size: 100% 20px;
        }
    </style>

    @stack('styles')
</head>

<body>

<main role="main">

    <div class="card ">
        <div class="card-header bg-primary text-white stripe">
            <strong style="font-size: 16px;">
                <i class="fa fa-server"></i> @yield('title')
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

<!-- Bootstrap core JavaScript
================================================== -->
<script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous">
</script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
    integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous">
</script>
<script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
    integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
    crossorigin="anonymous">
</script>

<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>

<script>
    $('[data-toggle="popover"]').popover({
        html: true,
        placement: 'top',
        trigger: 'hover'
    });

    $('[data-toggle="tooltip"]').tooltip();
</script>

@stack('scripts')

</body>
</html>
