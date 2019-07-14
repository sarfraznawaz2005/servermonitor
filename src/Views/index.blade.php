@extends('servermonitor::layout.layout')

@section('title', $title)

@section('header')
    <button type="submit" id="btnRefresh" class="btn btn-light btn-block">
        <i class="fa fa-play"></i> Run All Checks
    </button>
@endsection

@section('content')

    @if (isset($checkResults['counts']))
        <div class="mx-auto" style="width: 70%; font-size: 16px; background: #fff; padding: 8px; margin-top: 15px; line-height: 100%;">
            <div class="float-left">
                <span class="badge-success badge">
                Passed: {{$checkResults['counts']['passed_checks_count']}}
                </span>
                <span class="badge-danger badge">
                Failed: {{$checkResults['counts']['failed_checks_count']}}
                </span>
                <span class="badge-primary badge">
                Total: {{$checkResults['counts']['total_checks_count']}}
                </span>
            </div>
            <div class="float-right">
                <span class="badge-success badge">Last Checked: {{$lastRun}}</span>
            </div>
            <div class="clearfix"></div>
        </div>

        @php unset($checkResults['counts']) @endphp
    @endif

    <div class="table-responsive-sm">
        <table class="table table-hover table-bordered table-sm mx-auto"
               cellspacing="0"
               style="font-size: 14px; color: #555; width: 70%;">
            <thead>
            <tr>
                <th style="text-align: center;" width="40">#</th>
                <th>Check Name</th>
                <th style="text-align: center;" width="150">Status</th>
                <th style="text-align: center;" width="50">Run</th>
            </tr>
            </thead>

            <tbody>
            @foreach($checkResults as $type => $checks)
                <tr>
                    <td colspan="99" align="center">
                        <span style="font-size: 12px;" class="badge-primary badge badge-pill">
                            {{strtoupper($type)}}
                        </span>
                    </td>
                </tr>
                @foreach($checks as $index => $check)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{++$index}}</td>
                        <td><strong>{{$check['name']}}</strong></td>
                        @php
                            $isOk = $check['status'] == 1;
                            $text = $isOk ? 'Passed':'Failed';
                            $icon = $isOk ? 'success' : 'danger';
                            $popover = $isOk ? '' : 'tabindex="0" data-toggle="popover" data-trigger="focus" title="Error Details" data-content="' . $check['error'] . '"';

                            echo "<td style='text-align: center;'><span style='font-size: 12px; padding-bottom:6px;' ' . $popover . ' class='col-sm-10 badge badge-$icon'>$text</span></td>";
                        @endphp
                        <td style="text-align: center;">
                        <span
                                class="btn btn-primary btn-sm refresh"
                                data-checker="{{$check['checker']}}"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Run this check"
                                style="font-size: 10px;">
                            <i class="fa fa-play" style="font-size: 14px;"></i>
                        </span>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
    <script>

        $('[data-toggle="popover"]').popover({
            html: true,
            placement: 'top',
            trigger: 'hover'
        });

        $('[data-toggle="tooltip"]').tooltip();

        // refresh all checks
        $('#btnRefresh').click(function () {
            $('body').loading({
                message: 'Running, please wait...',
                stoppable: false
            });

            $.get('{{route('servermonitor_refresh_all')}}', function () {
                window.location.reload();
            });
        });

        // refresh single check
        $('.refresh').click(function () {
            $('body').loading({
                message: 'Running, please wait...',
                stoppable: false
            });

            $.get('{{route('servermonitor_refresh')}}', {check: $(this).data('checker')}, function (result) {
                $('body').loading('stop');

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
