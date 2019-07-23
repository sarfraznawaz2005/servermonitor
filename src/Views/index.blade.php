@extends('servermonitor::layout.layout')

@section('title', $title)

@section('header')
    <button type="submit" id="btnRefresh" class="btn btn-light btn-block">
        <img style="padding-bottom: 1px;"
             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAARCAYAAAA7bUf6AAAA8UlEQVQ4jaXUPUoEQRCG4acXEZFFREQ8ieEGBkYGnsDYM3gFEQNDIwPBwEDZTDAw8wCiBhqaLAbiLy5qmUzLuuzPzFhFUdBdvNTXRVcKIUmzWMBtiG8VrVHkJRxgI0lzVSFCwCoCzzjDCqZDOe+H5HjHHlpo1IUEvnCHTSzWheT4wDXWMT+oszKQXoknRe1kXUiOe2z3SswjLmufeCpAb/lwogLgEYfYD3Hx56aEnFecYxkzg6YzqpMuLrGD4xAvwwqHQR6wi6MQV+N09kM6OMUWbkJ0xwHw+yYttLGGqbJ/JnsqVkETTXTqrIJUdPIv+wFT0tX7APfQwQAAAABJRU5ErkJggg=="
             alt="Refresh">

        Run All Checks
    </button>
@endsection

@section('content')

    @if (isset($checkResults['counts']))
        <div class="mx-auto"
             style="width: 70%; font-size: 16px; background: #eee; padding: 10px; margin-top: 15px; line-height: 100%;">
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

        <?php unset($checkResults['counts']) ?>
    @endif

    <div class="table-responsive-sm">
        <table class="table table-hover table-bordered table-sm mx-auto"
               cellspacing="0"
               style="font-size: 14px; color: #555; width: 70%;">
            <thead>
            <tr>
                <th style="text-align: center;" width="40">#</th>
                <th>Check Name</th>
                <th style="text-align: center;" width="50">Time</th>
                <th style="text-align: center;" width="150">Status</th>
                <th style="text-align: center;" width="50">Run</th>
            </tr>
            </thead>

            <tbody>
            @foreach($checkResults as $type => $checks)
                <tr>
                    <td colspan="5" style="text-align: center; color:#333; background: #eee; font-size: 13px;">
                        <strong>{{strtoupper(str_replace('.', ' ', $type))}}</strong>
                    </td>
                </tr>
                @foreach($checks as $index => $check)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{++$index}}</td>
                        <td><strong>{{ucwords($check['name'])}}</strong></td>
                        <td style="text-align: center;">{{$check['time']}}</td>
                        <?php
                        $isOk = $check['status'] == 1;
                        $text = $isOk ? 'Passed' : 'Failed';
                        $icon = $isOk ? 'success' : 'danger';
                        $popover = $isOk ? '' : 'tabindex="0" data-toggle="popover" data-trigger="focus" title="Error Details" data-content="' . $check['error'] . '"';

                        echo "<td style='text-align: center;'><span style='font-size: 12px; padding-bottom:6px;' ' . $popover . ' class='col-sm-10 badge badge-$icon'>$text</span></td>";
                        ?>
                        <td style="text-align: center;">
                        <span
                                class="btn btn-info btn-sm refresh"
                                data-checker="{{$check['checker']}}"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Run this check"
                                style="font-size: 10px;">

                                <img style="padding-left: 5px;"
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
