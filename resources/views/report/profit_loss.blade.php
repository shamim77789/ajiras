@extends('layouts.master')
@section('title')
    {{trans_choice('general.profit_loss',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{trans_choice('general.profit_loss',1)}}
                @if(!empty($start_date))
                    for period: <b>{{$start_date}} to {{$end_date}}</b>
                @endif
            </h3>

            <div class="box-tools pull-right">
                <button class="btn btn-sm btn-info hidden-print" onclick="window.print()">Print</button>
            </div>
        </div>
        <div class="box-body hidden-print">
            <h4 class="">{{trans_choice('general.date',1)}} {{trans_choice('general.range',1)}}</h4>
            {!! Form::open(array('url' => Request::url(), 'method' => 'get','class'=>'form-horizontal', 'name' => 'form')) !!}
            <div class="row">
                <div class="col-md-4">
                    {!! Form::text('start_date',$start_date, array('class' => 'form-control date-picker', 'placeholder'=>"From Date",'required'=>'required')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::text('end_date',$end_date, array('class' => 'form-control date-picker', 'placeholder'=>"To Date",'required'=>'required')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control ','placeholder'=>'Select Branch','id'=>'')) !!}
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <span class="input-group-btn">
                          <button type="submit" class="btn bg-olive btn-flat">{{trans_choice('general.search',1)}}!
                          </button>
                        </span>
                        <span class="input-group-btn">
                            <a href="{{Request::url()}}"
                               class="btn bg-purple  btn-flat pull-right">{{trans_choice('general.reset',1)}}!</a>
                        </span>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
        <!-- /.box-body -->

    </div>
    <!-- /.box -->
    <div class="row">
        <div class="col-md-4">
            <table id="profitloss" class="table table-bordered table-hover " style="background: #FFF;">
                <tbody>
                <tr style="background: #CCC;">
                    <td style="font-weight:bold">{{trans_choice('general.profit_loss',1)}} {{trans_choice('general.statement',1)}}</td>
                    <td align="right" style="font-weight:bold">{{trans_choice('general.balance',1)}}</td>
                </tr>
                <tr class="bg-green">
                    <td style="font-weight:bold">{{trans_choice('general.operating_profit',1)}} (P)</td>
                    <td style="font-weight:bold"></td>
                </tr>
                <tr>
                    <td> {{trans_choice('general.contribution',2)}}</td>
                    <td align="right">{{number_format($contributions,2)}}</td>
                </tr>
                <tr>
                    <td> {{trans_choice('general.pledge',2)}}</td>
                    <td align="right">{{number_format($pledges,2)}}</td>
                </tr>
                <tr>
                    <td><b>{{trans_choice('general.event',1)}} {{trans_choice('general.payment',2)}}</b></td>
                    <td style="text-align:right">{{number_format($events,2)}}</td>
                </tr>
                <tr>
                    <td>{{trans_choice('general.other_income',2)}}</td>
                    <td align="right">{{number_format($other_income,2)}}</td>
                </tr>
                <tr class="bg-red">
                    <th>{{trans_choice('general.operating_expense',2)}} (E)
                    </th>
                    <th>
                    </th>
                </tr>
                <tr>
                    <td>{{trans_choice('general.payroll',1)}}</td>
                    <td align="right">{{number_format($payroll,2)}}</td>
                </tr>
                <tr>
                    <td>{{trans_choice('general.expense',2)}}</td>
                    <td align="right">{{number_format($expenses,2)}}</td>
                </tr>

                <tr style="background: #CCC;">
                    <td style="font-weight:bold">{{trans_choice('general.net',1)}} {{trans_choice('general.income',1)}}
                        (N) = P - E
                    </td>
                    <td style="font-weight:bold" align="right">{{number_format($net_profit,2)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 hidden-print">
            <!-- AREA CHART -->
            <!-- LINE CHART -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{trans_choice('general.monthly',1)}} {{trans_choice('general.net',1)}} {{trans_choice('general.income',1)}}</h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="netIncomeChart" style="height: 250px;">
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <div class="box box-primary hidden-print">
                <div class="box-header with-border">
                    <h3 class="box-title"><span
                                style="color: #00a65a">{{trans_choice('general.operating_profit',1)}}</span>
                        / {{trans_choice('general.operating_expense',1)}}</h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart" id="operatingProfit" style="height: 350px;">
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- LINE CHART -->
            <div class="box box-info hidden-print">
                <div class="box-header with-border">
                    <h3 class="box-title">{{trans_choice('general.income',1)}} {{trans_choice('general.overview',2)}}</h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body" style="display: block;">
                    <div class="chart" id="overviewChart" style="height: 350px;">
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/amcharts/amcharts.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/serial.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/pie.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/themes/light.js') }}"
            type="text/javascript"></script>

    <script>
        AmCharts.makeChart("netIncomeChart", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": {!! $monthly_net_income_data !!},
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#370fc6",
                "lineThickness": 4,
                "negativeLineColor": "#b61901",
                "title": "{{trans_choice('general.net',1)}} {{trans_choice('general.income',1)}}",
                "type": "smoothedLine",
                "valueField": "amount"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            },
            "export": {
                "enabled": true,
                "libs": {
                    "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                }
            }


        });
        AmCharts.makeChart("operatingProfit", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": {!! $monthly_operating_profit_expenses_data !!},
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "lineAlpha": 0,
                "fillColors": "#00a65a",
                "fillAlphas": 1,
                "title": "{{trans_choice('general.profit',1)}}",
                "type": "column",
                "valueField": "profit"
            }, {
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "lineAlpha": 0,
                "fillColors": "#b61901",
                "fillAlphas": 1,
                "title": "{{trans_choice('general.expense',2)}}",
                "type": "column",
                "valueField": "expenses"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            },
            "export": {
                "enabled": true,
                "libs": {
                    "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                }
            }


        }).addLegend(new AmCharts.AmLegend());
        AmCharts.makeChart("overviewChart", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": {!! $monthly_overview_data !!},
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#1bd126",
                "lineThickness": 4,
                "negativeLineColor": "#b6481e",
                "title": " {{trans_choice('general.contribution',2)}}",
                "type": "smoothedLine",
                "valueField": "contributions"
            }, {
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#4846d1",
                "lineThickness": 4,
                "negativeLineColor": "#b6481e",
                "title": " {{trans_choice('general.pledge',2)}}",
                "type": "smoothedLine",
                "valueField": "pledges"
            }, {
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#d1cf0d",
                "lineThickness": 4,
                "negativeLineColor": "#b6481e",
                "title": " {{trans_choice('general.other_income',2)}}",
                "type": "smoothedLine",
                "valueField": "other_income"
            }, {
                "balloonText": "<span style='font-size:13px;'>[[title]] in [[category]]:<b> [[value]]</b> [[additional]]</span>",
                "bullet": "round",
                "bulletSize": 8,
                "lineColor": "#ff8b39",
                "lineThickness": 4,
                "negativeLineColor": "#ff8b39",
                "title": " {{trans_choice('general.event',1)}} {{trans_choice('general.payment',2)}}",
                "type": "smoothedLine",
                "valueField": "events"
            }],
            "categoryField": "month",
            "categoryAxis": {
                "gridPosition": "start",
                "axisAlpha": 0,
                "tickLength": 0,
                "labelRotation": 30,

            },
            "export": {
                "enabled": true,
                "libs": {
                    "path": "{{asset('assets/plugins/amcharts/plugins/export/libs')}}/"
                }
            }

        }).addLegend(new AmCharts.AmLegend());

    </script>

@endsection
