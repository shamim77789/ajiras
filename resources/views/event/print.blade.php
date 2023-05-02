<link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">
                <b>{{\App\Models\Setting::where('setting_key','company_name')->first()->setting_value}}</b></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
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
                    <td><b>{{trans_choice('general.created_at',1)}}</b></td>
                    <td>{{$event->created_at}}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2><b>{{trans_choice('general.note',2)}} </b></h2>
            <p>{!! $event->notes !!}</p>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        window.print();
    }
</script>