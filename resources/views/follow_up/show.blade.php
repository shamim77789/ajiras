@extends('layouts.master')
@section('title')
    {{trans_choice('general.follow_up',2)}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-body">
                    <table class="table table-striped">
                        <tr>
                            <td>{{trans_choice('general.id',1)}}</td>
                            <td>{{ $follow_up->id }}</td>
                        </tr>
                        <tr>
                            <td>{{trans_choice('general.assigned_to',1)}}</td>
                            <td>
                                @if(!empty($follow_up->assigned_to))
                                    <a href="{{url('user/'.$follow_up->assigned_to->id.'/show')}}">{{$follow_up->assigned_to->first_name}} {{$follow_up->assigned_to->last_name}}</a>

                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{trans_choice('general.member',1)}}</td>
                            <td>
                                @if(!empty($follow_up->member))
                                    <a href="{{url('member/'.$follow_up->member->id.'/show')}}">{{$follow_up->member->first_name}} {{$follow_up->member->middle_name}} {{$follow_up->member->last_name}}</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{trans_choice('general.category',1)}}</td>
                            <td>
                                @if(!empty($follow_up->category))
                                    {{$follow_up->category->name}}

                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{trans_choice('general.status',1)}}</td>
                            <td>
                                @if($follow_up->status==1)
                                    <span class="label label-success">{{ trans_choice('general.complete',1) }}</span>
                                @else
                                    <span class="label label-warning">{{ trans_choice('general.incomplete',1) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>{{trans_choice('general.created_at',2)}}</td>
                            <td>
                                {{ $follow_up->created_at }}
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{trans_choice('general.note',2)}} </h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                <div class="box-body">
                    <h4 class="">{{trans_choice('general.follow_up',1)}}  {{trans_choice('general.note',2)}} </h4>
                    <p>{!! $follow_up->notes !!}</p>
                    <h4 class="">{{trans_choice('general.category',1)}}  {{trans_choice('general.note',2)}} </h4>
                    @if(!empty($follow_up->category))
                        <p>{!! $follow_up->category->notes !!}</p>

                    @endif
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <!-- /.box -->
@endsection
@section('footer-scripts')

@endsection
