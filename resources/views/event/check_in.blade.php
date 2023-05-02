@extends('layouts.master')
@section('title')
    {{$event->name}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{url('event/'.$event->id.'/show')}}" class="list-group-item">
                    <i class="fa fa-bar-chart"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.overview',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/attender')}}" class="list-group-item ">
                    <i class="fa fa-user"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.attender',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/report')}}" class="list-group-item">
                    <i class="fa fa-th"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.report',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/check_in')}}" class="list-group-item active">
                    <i class="fa fa-check"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.check_in',2)}}
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
                    <h3 class="box-title">{{trans_choice('general.check_in',2)}}</h3>

                    <div class="box-tools pull-right">
                        <a href="#"
                           class="btn btn-success btn-sm" data-toggle="modal" data-target="#addCheckin"><i
                                    class="fa fa-plus"></i> {{trans_choice('general.check_in',1)}}
                        </a>
                        <a href="#"
                           class="btn btn-info btn-sm" data-toggle="modal" data-target="#smsMember"><i
                                    class="fa fa-mobile"></i> {{trans_choice('general.sms',1)}} {{trans_choice('general.member',2)}}
                        </a>
                        <a href="#"
                           class="btn btn-success btn-sm" data-toggle="modal" data-target="#emailMember"><i
                                    class="fa fa-envelope"></i> {{trans_choice('general.email',1)}} {{trans_choice('general.member',2)}}
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered table-condensed table-hover">
                            <thead>
                            <tr style="background-color: #D1F9FF">
                                <th>{{trans_choice('general.id',1)}}</th>
                                <th>{{trans_choice('general.name',1)}}</th>
                                <th>{{trans_choice('general.photo',1)}}</th>
                                <th>{{trans_choice('general.phone',1)}}</th>
                                <th>{{trans_choice('general.gender',1)}}</th>
                                <th>{{trans_choice('general.age',1)}}</th>
                                <th>{{trans_choice('general.status',1)}}</th>
                                <th>{{ trans_choice('general.action',1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($event->attenders as $key)
                                @if(!empty($key->member))
                                    <tr>
                                        <td>#{{$key->id}}</td>
                                        <td>
                                            <a href="{{url('member/'.$key->id.'/show')}}">{{$key->first_name}} {{$key->middle_name}} {{$key->last_name}}</a>
                                        </td>
                                        <td>
                                            @if(!empty($key->photo))
                                                <a class="fancybox" rel="group"
                                                   href="{{ url(asset('uploads/'.$key->photo)) }}"> <img
                                                            src="{{ url(asset('uploads/'.$key->photo)) }}" width="100"/></a>
                                            @else
                                                <img class="img-thumbnail"
                                                     src="{{asset('assets/dist/img/user.png')}}"
                                                     alt="user image" style="max-height: 70px!important;"/>
                                            @endif
                                        </td>
                                        <td>{{$key->mobile_phone}}</td>
                                        <td>
                                            @if($key->gender=="male")
                                                {{trans_choice('general.male',1)}}
                                            @endif
                                            @if($key->gender=="female")
                                                {{trans_choice('general.female',1)}}
                                            @endif
                                            @if($key->gender=="unknown")
                                                {{trans_choice('general.unknown',1)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($key->dob))
                                                {{date("Y-m-d")-$key->dob}}
                                            @endif
                                        </td>
                                        <td>{!! $key->address !!}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    {{ trans('general.choose') }} <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    @if(Sentinel::hasAccess('members.view'))
                                                        <li><a href="{{ url('member/'.$key->id.'/show') }}"><i
                                                                        class="fa fa-search"></i> {{trans_choice('general.detail',2)}}
                                                            </a></li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('members.update'))
                                                        <li><a href="{{ url('member/'.$key->id.'/edit') }}"><i
                                                                        class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('members.delete'))
                                                        <li><a href="{{ url('member/'.$key->id.'/delete') }}"
                                                               class="delete"><i
                                                                        class="fa fa-trash"></i> {{ trans('general.delete') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCheckin">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.check_in',2)}} {{trans_choice('general.member',1)}}</h4>
                </div>
                {!! Form::open(array('url' => url('event/add_checkin'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$event->id}}" name="event_id">
                    <div class="form-group">
                        <div class="form-line">
                            {!!  Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                            {!! Form::select('member_id',$members,null,array('class'=>' select2','required'=>'required','id'=>'','placeholder'=>'')) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">{{trans_choice('general.save',2)}}</button>
                    <button type="button" class="btn default"
                            data-dismiss="modal">{{trans_choice('general.close',2)}}</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('footer-scripts')
    <script>
        $(document).ready(function (e) {

        })
    </script>
@endsection
