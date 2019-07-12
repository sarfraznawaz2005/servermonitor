@extends('servermonitor::layout.layout')

@section('title', $title)

@section('header')
    <button type="submit" id="btnRefresh" class="btn btn-warning btn-sm">
        <i class="fa fa-refresh"></i> Run All Checks
    </button>
@endsection

@section('content')

    <div class="text-center">
        <span class="badge-success badge" style="font-size: 12px;">Last Checked: {{$lastRun}}</span>
    </div>

    <table class="table mx-auto" cellspacing="0" style="font-size: 14px; color: #555; width: 80%;">
        <thead>
        <tr>
            <th style="text-align: center;" width="1">#</th>
            <th>Check Type</th>
            <th>Check Name</th>
            <th>Status</th>
            <th style="text-align: center;" width="1">Run</th>
        </tr>
        </thead>

        <tbody>
        @foreach($checkResults as $type => $checks)
            @foreach($checks as $index => $check)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{++$index}}</td>
                    <td>{{strtoupper($check['type'])}}</td>
                    <td><strong>{{$check['name']}}</strong></td>
                    @php
                        $isOk = $check['status'] == 1;
                        $text = $isOk ? 'Passed':'Failed';
                        $icon = $isOk ? 'success' : 'danger';
                        $popover = $isOk ? '' : 'tabindex="0" data-toggle="popover" data-trigger="focus" title="Error Details" data-content="' . $check['error'] . '"';

                        echo "<td><span ' . $popover . ' class='col-sm-10 badge badge-$icon'>$text</span></td>";
                    @endphp
                    <td style="text-align: center;">
                        <span
                                class="btn btn-primary btn-sm refresh"
                                id="{{$check['checker']}}"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Run this check"
                                style="font-size: 10px;">
                            <i class="fa fa-refresh" style="font-size: 10px;"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>

    <div id="overlay">
        <div class="spinner"></div>
        <span class="overlay-message">Working, please wait...</span>
    </div>

@endsection

@push('styles')
    <style>
        #overlay {
            position: fixed;
            display: none;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999999999;
        }

        #overlay .overlay-message {
            position: fixed;
            left: 50%;
            top: 57%;
            height: 100px;
            width: 250px;
            margin-left: -120px;
            margin-top: -50px;
            color: #fff;
            font-size: 20px;
            text-align: center;
            font-weight: bold;
        }

        .spinner {
            position: fixed;
            left: 50%;
            top: 40%;
            height: 80px;
            width: 80px;
            margin-left: -40px;
            margin-top: -40px;
            -webkit-animation: rotation .9s infinite linear;
            -moz-animation: rotation .9s infinite linear;
            -o-animation: rotation .9s infinite linear;
            animation: rotation .9s infinite linear;
            border: 6px solid rgba(255, 255, 255, .15);
            border-top-color: rgba(255, 255, 255, .8);
            border-radius: 100%;
        }

        @-webkit-keyframes rotation {
            from {
                -webkit-transform: rotate(0deg);
            }
            to {
                -webkit-transform: rotate(359deg);
            }
        }

        @-moz-keyframes rotation {
            from {
                -moz-transform: rotate(0deg);
            }
            to {
                -moz-transform: rotate(359deg);
            }
        }

        @-o-keyframes rotation {
            from {
                -o-transform: rotate(0deg);
            }
            to {
                -o-transform: rotate(359deg);
            }
        }

        @keyframes rotation {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(359deg);
            }
        }

        table.dataTable tr.group td {
            background-image: radial-gradient(#fff, #eee);
            border: none;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
    </style>
@endpush

@push('scripts')
    <script>

        $('[data-toggle="popover"]').popover({
            html: true,
            placement: 'top',
            trigger: 'hover'
        });

        $('[data-toggle="tooltip"]').tooltip();

        $('.table').DataTable({
            "order": [],
            "responsive": true,
            "paging": false,
            "info": false,
            "searching": false,
            "sort": false,
            "pageLength": 100,
            "autoWidth": true,
            aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [-1]
                },
                {
                    visible: false,
                    aTargets: [1]
                }
            ],
            rowGroup: {
                dataSrc: 1
            }
        });

        // refresh all checks
        $('#btnRefresh').click(function () {
            $('#overlay').show();

            $.get('{{route('servermonitor_refresh_all')}}', function () {
                window.location.reload();
            });
        });

        // refresh single check
        $('.refresh').click(function () {
            $('#overlay').show();

            $.get('{{route('servermonitor_refresh')}}', {check: this.id}, function (result) {
                $('#overlay').hide();

                if (result.status) {
                    swal("Passed", "Check Passed Successfully!", "success");
                }
                else {
                    swal("Failed", result.error, "error");
                }
            });
        });

    </script>
@endpush
