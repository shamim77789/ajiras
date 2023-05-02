@extends('layouts.master')
@section('title')
    {{$event->name}}
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/plugins/amcharts/plugins/export/export.css') }}" type="text/css"
          media="all"/>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{url('event/'.$event->id.'/show')}}" class="list-group-item">
                    <i class="fa fa-bar-chart"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.overview',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/attender')}}" class="list-group-item ">
                    <i class="fa fa-user"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.attender',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/report')}}" class="list-group-item active">
                    <i class="fa fa-th"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.report',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/volunteer')}}" class="list-group-item ">
                    <i class="fa fa-group"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.volunteer',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/payment')}}" class="list-group-item">
                    <i class="fa fa-money"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.payment',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/edit')}}" class="list-group-item">
                    <i class="fa fa-edit"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.edit',2)}}
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{trans_choice('general.report',2)}}</h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                <div class="box-body">
                    <h4><b>{{trans_choice('general.total',1)}} {{trans_choice('general.attendance',1)}}
                            :</b> {{\App\Models\EventAttendance::where('event_id',$event->id)->count()}}</h4>
                    @if(\App\Models\Event::where('parent_id',$event->id)->count()>0)

                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{trans_choice('general.gender',1)}}</h4>
                            <hr>
                            <div id="gender_graph" style="height: 350px"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{trans_choice('general.age',1)}}</h4>
                            <hr>
                            <div id="age_graph" style="height: 350px"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{trans_choice('general.marital_status',1)}}</h4>
                            <hr>
                            <div id="marital_status_graph" style="height: 350px"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4>{{trans_choice('general.status',1)}}</h4>
                            <hr>
                            <div id="status_graph" style="height: 350px"></div>
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="{{ asset('assets/plugins/amcharts/plugins/export/export.min.js') }}"
            type="text/javascript"></script>
    <script>
        AmCharts.makeChart("gender_graph", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": [{
                "type": "{{trans_choice('general.male',1)}}",
                "count": "{{$data["male"]}}",
            },
                {
                    "type": "{{trans_choice('general.female',1)}}",
                    "count": "{{$data["female"]}}",
                }, {
                    "type": "{{trans_choice('general.unknown',1)}}",
                    "count": "{{$data["unassigned_gender"]}}",
                }
            ],
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'> [[category]]:<b> [[value]] {{trans_choice('general.people',1)}}</b> [[additional]]</span>",
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "count"
            }],
            "categoryField": "type",
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
        AmCharts.makeChart("age_graph", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": [{
                "type": "Under 6",
                "count": "{{$data["under6"]}}",
            },
                {
                    "type": "6-12",
                    "count": "{{$data["six12"]}}",
                }, {
                    "type": "13-18",
                    "count": "{{$data["thirteen18"]}}",
                }, {
                    "type": "19-29",
                    "count": "{{$data["nineteen29"]}}",
                }, {
                    "type": "30-49",
                    "count": "{{$data["thirty49"]}}",
                }, {
                    "type": "50-64",
                    "count": "{{$data["fifty64"]}}",
                }, {
                    "type": "65-79",
                    "count": "{{$data["sixty_five79"]}}",
                }, {
                    "type": "80+",
                    "count": "{{$data["eight_plus"]}}",
                }, {
                    "type": "{{trans_choice('general.unknown',1)}}",
                    "count": "{{$data["unassigned_age"]}}",
                }
            ],
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'> [[category]]:<b> [[value]] {{trans_choice('general.people',1)}}</b> [[additional]]</span>",
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "count"
            }],
            "categoryField": "type",
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
        AmCharts.makeChart("marital_status_graph", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": [{
                "type": "{{trans_choice('general.married',1)}}",
                "count": "{{$data["married"]}}",
            },
                {
                    "type": "{{trans_choice('general.engaged',1)}}",
                    "count": "{{$data["engaged"]}}",
                }, {
                    "type": "{{trans_choice('general.separated',1)}}",
                    "count": "{{$data["separated"]}}",
                }, {
                    "type": "{{trans_choice('general.widowed',1)}}",
                    "count": "{{$data["widowed"]}}",
                }, {
                    "type": "{{trans_choice('general.divorced',1)}}",
                    "count": "{{$data["divorced"]}}",
                }, {
                    "type": "{{trans_choice('general.single',1)}}",
                    "count": "{{$data["single"]}}",
                }, {
                    "type": "{{trans_choice('general.unknown',1)}}",
                    "count": "{{$data["unassigned_marital_status"]}}",
                }
            ],
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'> [[category]]:<b> [[value]] {{trans_choice('general.people',1)}}</b> [[additional]]</span>",
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "count"
            }],
            "categoryField": "type",
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
        AmCharts.makeChart("status_graph", {
            "type": "serial",
            "theme": "light",
            "autoMargins": true,
            "marginLeft": 30,
            "marginRight": 8,
            "marginTop": 10,
            "marginBottom": 26,
            "fontFamily": 'Open Sans',
            "color": '#888',

            "dataProvider": [{
                "type": "{{trans_choice('general.attender',2)}}",
                "count": "{{$data["attender"]}}",
            },
                {
                    "type": "{{trans_choice('general.visitor',2)}}",
                    "count": "{{$data["visitor"]}}",
                }, {
                    "type": "{{trans_choice('general.member',2)}}",
                    "count": "{{$data["member"]}}",
                }, {
                    "type": "{{trans_choice('general.inactive',1)}}",
                    "count": "{{$data["inactive"]}}",
                }, {
                    "type": "{{trans_choice('general.unknown',1)}}",
                    "count": "{{$data["unassigned_status"]}}",
                }
            ],
            "valueAxes": [{
                "axisAlpha": 0,

            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "<span style='font-size:13px;'> [[category]]:<b> [[value]] {{trans_choice('general.people',1)}}</b> [[additional]]</span>",
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "count"
            }],
            "categoryField": "type",
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

    </script>
    <script>
        $(document).ready(function (e) {

        })
    </script>
@endsection
