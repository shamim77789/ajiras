@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.campaign',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.campaign',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('pledge/campaign/store'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.name',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('name',null, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
			<div class="form-group">
				{!! Form::label('name','Campaign Type',array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select name="pledge_type" class="form-control">
						<option value="0">Select Pledge Type</option>
						@if(!empty($pledge_type))
							@foreach($pledge_type as $type)
								<option value="{{$type->id}}">{{$type->name}}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('name',trans_choice('general.fund',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select class="fund form-control" name="fund">
							<option value="0">Select Fund</option>
							@if(!empty($funds))
							@foreach($funds as $fund)
								<option value="{{$fund->id}}" >{{$fund->name}}</option>
							@endforeach
							@endif
					</select>
                </div>
            </div>		
			<div class="form-group">
                {!! Form::label('goal',trans_choice('general.goal',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('goal',null, array('class' => 'form-control touchspin', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('start_date',trans_choice('general.start',1).' '.trans_choice('general.date',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('start_date',date("Y-m-d"), array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('end_date',trans_choice('general.end',1).' '.trans_choice('general.date',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('end_date',null, array('class' => 'form-control date-picker', 'placeholder'=>"",''=>'')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('notes',null, array('class' => 'form-control', 'placeholder'=>"",'rows'=>'3')) !!}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection

