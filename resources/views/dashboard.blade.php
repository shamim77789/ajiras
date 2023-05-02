@extends('layouts.master')
@section('title')
    {{ trans('general.dashboard') }}
@endsection


@section('content')
    <div class="row">
        @if(Sentinel::hasAccess('dashboard.members_statistics'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                <span class="info-box-text">{{ trans_choice('general.registered',1) }}
                    <br>{{ trans_choice('general.member',2) }}</span>
                        <span class="info-box-number">{{ \App\Models\Member::count() }}</span>
                    </div>
                </div>
            </div>
        @endif
        @if(Sentinel::hasAccess('dashboard.tags_statistics'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-tags"></i></span>

                    <div class="info-box-content">
                <span class="info-box-text">{{ trans_choice('general.total',1) }}
                    <br>{{ trans_choice('general.tag',2) }}</span>
                        <span class="info-box-number">{{ \App\Models\Tag::count() }}</span>
                    </div>
                </div>
            </div>
        @endif
        <div class="clearfix visible-sm-block"></div>
        @if(Sentinel::hasAccess('dashboard.contributions_statistics'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                <span class="info-box-text">{{ trans_choice('general.total',1) }}
                    <br>{{ trans_choice('general.contribution',2) }}</span>
                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                            <span class="info-box-number"> {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }} {{ number_format(\App\Helpers\GeneralHelper::total_contributions(),2) }} </span>
                        @else
                            <span class="info-box-number"> {{ number_format(\App\Helpers\GeneralHelper::total_contributions(),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</span>
                        @endif

                    </div>
                </div>
            </div>
        @endif
        <div class="clearfix visible-sm-block"></div>
        @if(Sentinel::hasAccess('dashboard.contributions_statistics'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-hand-lizard-o"></i></span>

                    <div class="info-box-content">
                <span class="info-box-text">{{ trans_choice('general.total',1) }}
                    <br>{{ trans_choice('general.pledge',2) }}</span>
                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                            <span class="info-box-number"> {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }} {{ number_format(\App\Helpers\GeneralHelper::total_pledges_payments(),2) }} </span>
                        @else
                            <span class="info-box-number"> {{ number_format(\App\Helpers\GeneralHelper::total_pledges_payments(),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</span>
                        @endif

                    </div>
                </div>
            </div>
        @endif

    </div>


    @if(Sentinel::hasAccess('dashboard.finance_graph'))
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><span
                                        style="color: #D72828">{{trans_choice('general.income',1)}} {{trans_choice('general.overview',1)}}</span></b>
                        </h3>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="overviewChart" class="chart" style="height: 350px;">
                        </div>
                    </div>
                </div>
                <!-- END PORTLET -->
            </div>
        </div>
    @endif
    @if(Sentinel::hasAccess('dashboard.calendar'))
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b><span
                                        style="">{{trans_choice('general.event',2)}} {{trans_choice('general.calendar',1)}}</span></b>
                        </h3>

                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div id="calendar" class="chart" style="">
                        </div>
                    </div>
                </div>
                <!-- END PORTLET -->
            </div>
        </div>
    @endif
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/fullcalendar/fullcalendar.js') }}"></script>

    <script src="{{ asset('assets/plugins/amcharts/amcharts.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/serial.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/pie.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/amcharts/themes/light.js') }}"
            type="text/javascript"></script>
    <script>
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
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: '{{trans_choice('general.today',1)}}',
                month: '{{trans_choice('general.month',1)}}',
                week: '{{trans_choice('general.week',1)}}',
                day: '{{trans_choice('general.day',1)}}'
            },
            //Random default events
            events: {!! $events !!},
            selectable: false,

        });
    </script>

@endsection
