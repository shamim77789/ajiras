@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.fund',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.batch',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('pledge/campaign/'.$campaign->id.'/update'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.name',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('name',$campaign->name, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
			<div class="form-group">
				{!! Form::label('name','Campaign Type',array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select name="pledge_type" class="form-control">
						<option value="0">Select Pledge Type</option>
						@if(!empty($pledge_type))
							@foreach($pledge_type as $type)
								<option value="{{$type->id}}" {{$campaign->pledge_type == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
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
								<option value="{{$fund->id}}" {{$campaign->fund == $fund->id ? 'selected' : ''}}>{{$fund->name}}</option>
							@endforeach
							@endif
					</select>
                </div>
            </div>			

			<div class="form-group">
                {!! Form::label('goal',trans_choice('general.goal',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('goal',$campaign->goal, array('class' => 'form-control touchspin', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('start_date',trans_choice('general.start',1).' '.trans_choice('general.date',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('start_date',$campaign->start_date, array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('end_date',trans_choice('general.end',1).' '.trans_choice('general.date',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('end_date',$campaign->end_date, array('class' => 'form-control date-picker', 'placeholder'=>"",''=>'')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('notes',$campaign->notes, array('class' => 'form-control', 'placeholder'=>"",'rows'=>'3')) !!}
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