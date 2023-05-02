@extends('layouts.master')
@section('title')
    {{$event->name}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{url('event/'.$event->id.'/show')}}" class="list-group-item ">
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
                <a href="{{url('event/'.$event->id.'/payment')}}" class="list-group-item active">
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
                    <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.payment',1)}}</h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                {!! Form::open(array('url' => url('event/'.$event->id.'/payment/store'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
                <div class="box-body">
                    <div class="form-group">
                        {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                        <div id="memberDetails">
                            {!! Form::select('member_id',$members,null, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'member_id')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('amount',trans_choice('general.amount',1),array('class'=>'')) !!}
                        {!! Form::text('amount',$event->cost, array('class' => 'form-control touchspin', 'placeholder'=>"",'required'=>'required')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('date',trans_choice('general.date',1),array('class'=>'')) !!}
                        {!! Form::text('date',date("Y-m-d"), array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('payment_method_id',trans_choice('general.payment',1).' '.trans_choice('general.method',1),array('class'=>' control-label')) !!}

                        {!! Form::select('payment_method_id',$payment_methods,null, array('class' => 'form-control select2','placeholder'=>'','id'=>'payment_method_id')) !!}

                    </div>
					<div class="form-group">
                        {!! Form::label('group',trans_choice('general.group',2),array('class'=>'')) !!}					
						<select class="group_id select2 form-control" name="group_id">
							<option value="0">Select Group</option>
							@if(!empty($groups))
								@foreach($groups as $group)
									<option value="{{$group->id}}">{{$group->group_name}}</option>
								@endforeach
							@endif
						</select>
					</div>
                    <div class="form-group">
                        {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'')) !!}
                        {!! Form::textarea('notes',null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary margin pull-right" name="save_return" value="save_return">{{ trans_choice('general.save',1) }} </button>
                </div>
                {!! Form::close() !!}
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
