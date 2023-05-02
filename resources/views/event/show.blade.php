@extends('layouts.master')
@section('title')
    {{$event->name}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{url('event/'.$event->id.'/show')}}" class="list-group-item active">
                    <i class="fa fa-bar-chart"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.overview',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/attender')}}" class="list-group-item">
                    <i class="fa fa-user"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.attender',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/report')}}" class="list-group-item">
                    <i class="fa fa-th"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.report',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/volunteer')}}" class="list-group-item">
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
                    <h3 class="box-title">{{trans_choice('general.overview',2)}}</h3>

                    <div class="box-tools pull-right">
                        <a href="{{ url('event/'.$event->id.'/print') }}" target="_blank"
                           class="btn btn-success btn-sm"><i
                                    class="fa fa-print"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td><b>{{trans_choice('general.name',1)}}</b></td>
                                    <td>{{$event->name}}</td>
                                </tr>
                                <tr>
                                    <td><b>{{trans_choice('general.date',1)}}</b></td>
                                    <td>
                                        <b>{{trans_choice('general.starts_on',1)}}
                                            :</b> {{$event->start_date}} @if(!empty($event->start_time))
                                            ({{$event->start_time}}) @endif<br>
                                        <b>{{trans_choice('general.ends_on',1)}}
                                            :</b> {{$event->end_date}} @if(!empty($event->end_time))
                                            ({{$event->end_time}}) @endif<br>
                                        @if($event->recurring==1) <span
                                                class="label label-success">{{ trans('general.recurring') }}</span> @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{trans_choice('general.cost',1)}}</b></td>
                                    <td>
                                        @if(!empty($event->cost) && $event->cost!=0 )
                                            {{$event->cost}}
                                        @else
                                            {{trans_choice('general.free',1)}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{trans_choice('general.calendar',1)}}</b></td>
                                    <td>
                                        @if(!empty($event->calendar) )
                                            {{$event->calendar->name}}
                                        @else
                                            {{trans_choice('general.main',1)}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{trans_choice('general.location',1)}}</b></td>
                                    <td>
                                        @if(!empty($event->location) )
                                            {{$event->location->name}}
                                        @else
                                            {{trans_choice('general.none',1)}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{trans_choice('general.featured_image',1)}}</b></td>
                                    <td>
                                        @if(!empty($event->featured_image))
                                            <a class="fancybox" rel="group"
                                               href="{{ asset('uploads/'.$event->featured_image) }}"> <img
                                                        src="{{ asset('uploads/'.$event->featured_image) }}"
                                                        class="img-responsive"/></a>
                                        @endif
                                    </td>
                                </tr>
								<tr>
									<td><b>{{trans_choice('general.batch',1)}}</b></td>
									<td>{{$batch->name}}</td>
								</tr>
                                <tr>
                                    <td><b>{{trans_choice('general.note',2)}}</b></td>
                                    <td>{!! $event->notes !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{trans_choice('general.created_at',1)}}</b></td>
                                    <td>{{$event->created_at}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if(!empty($event->latitude) && !empty($event->longitude))
                        <div id="map" style="height: 400px; width: 100%;">
                        </div>
                        <script src="http://maps.google.com/maps/api/js?sensor=false&key={{\App\Models\Setting::where('setting_key','google_maps_key')->first()->setting_value}}"
                                type="text/javascript"></script>
                        <script type="text/javascript">
                            var locations = [
                                ['<strong style="color:#370fc6">{{$event->name}}</strong><br>{{$event->start_date}} to {{$event->end_date}}', {{$event->latitude}}, {{$event->longitude}}]
                            ];

                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 9,
                                center: new google.maps.LatLng({{$event->latitude}}, {{$event->longitude}}),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            for (i = 0; i < locations.length; i++) {
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                    map: map
                                });

                                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                    return function () {
                                        infowindow.setContent(locations[i][0]);
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));
                            }
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>


@endsection
@section('footer-scripts')
    <script>
        $(document).ready(function (e) {

        })
    </script>
@endsection
