@extends('servermonitor::layout.layout')

@section('title', $title)

@section('header')
    <button type="submit" id="btnRefresh" class="btn btn-light btn-block">
        <img class="pb-0"
             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAAA8UlEQVQ4jaXUPUoEQRCG4acXEZFFREQ8ieEGBkYGnsDYM3gFEQNDIwPBwEDZTDAw8wCiBhqaLAbiLy5qmUzLuuzPzFhFUdBdvNTXRVcKIUmzWMBtiG8VrVHkJRxgI0lzVSFCwCoCzzjDCqZDOe+H5HjHHlpo1IUEvnCHTSzWheT4wDXWMT+oszKQXoknRe1kXUiOe2z3SswjLmufeCpAb/lwogLgEYfYD3Hx56aEnFecYxkzg6YzqpMuLrGD4xAvwwqHQR6wi6MQV+N09kM6OMUWbkJ0xwHw+yYttLGGqbJ/JnsqVkETTXTqrIJUdPIv+wFT0tX7APfQwQAAAABJRU5ErkJggg=="
             alt="Refresh">

        Run All Checks
    </button>
@endsection

@section('content')

    @if (isset($checkResults['counts']))
        <div class="mx-auto w-75 mt-3 h-auto bg-transparent">
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
                <span class="badge-light badge">Last Checked: {{$lastRun}} via {{$checkResults['via']}}</span>
            </div>
            <div class="clearfix"></div>
        </div>

        <?php unset($checkResults['counts'], $checkResults['via']) ?>
    @endif

    <div class="table-responsive-sm">
        <table class="table table-hover table-bordered table-sm mx-auto small w-75 text-dark">
            <thead>
            <tr>
                <th class="text-center" style="width: 40px;">#</th>
                <th>Check Name</th>
                <th class="text-center" style="width: 50px;">Time</th>
                <th class="text-center" style="width: 150px;">Status</th>
                <th class="text-center" style="width: 50px;">Run</th>
            </tr>
            </thead>

            <tbody>
            @foreach($checkResults as $type => $checks)
                <tr>
                    <td colspan="5" class="text-center text-dark text-uppercase" style="background: #eee;">
                        <strong>{{str_replace('.', ' ', $type)}}</strong>
                    </td>
                </tr>
                @foreach($checks as $index => $check)
                    <tr>
                        <td class="text-center">{{++$index}}</td>
                        <td class="font-weight-bold">{{ucwords($check['name'])}}</td>
                        <td class="text-center">{{$check['time']}}</td>
                        <?php
                        $isOk = $check['status'] == 1;
                        $text = $isOk ? 'Passed' : 'Failed';
                        $icon = $isOk ? 'success' : 'danger';
                        $popover = $isOk ? '' : 'tabindex="0" data-toggle="popover" data-trigger="focus" title="Error Details" data-content="' . $check['error'] . '"';

                        echo "<td class='text-center'><span style='font-size: 12px; padding-bottom:6px;' ' . $popover . ' class='col-sm-10 badge badge-$icon'>$text</span></td>";
                        ?>
                        <td class='text-center'>
                        <span
                                class="btn btn-info btn-sm refresh"
                                data-checker="{{$check['checker']}}"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Run this check"
                                style="font-size: 10px;">

                                <img class="pl-1"
                                     src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAYAAAA71pVKAAAA/ElEQVQokZ2SvUoDURSEZ5YgIqksrHwAK2tLH0Cs8gi+jIid1uIPWKfNA9hoYWevINoIQhDRMJ9FdsOy7K7RORwOXM7HcOdeIyS0Dgxd+FF/UFHOHUmXhD2hlaVphIARc70D4yTbSVZRfwmhJKMkVA08JTkCNpeCaSjJN3ALHCQZAkUrDIxKRzrmOMk+MOiEu7rUa5JDYKOCB1VwtvtynQFvth+AqTXfHfQRpT4knUi6lnVfgQsYaHOeAhPbx8CdC381FxbOQP38xva57QtZn3W3Vth2JD1LupJ0Cry48Kz3QmXau0nOkmz99qvq5fK91iQVsqa9Tg3N4X/qByQpP53UQDGqAAAAAElFTkSuQmCC"
                                     alt="Refresh">
                        </span>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
