@extends('layouts.master')
@section('title')
    {{ trans_choice('general.add',1) }} {{ trans_choice('general.announcement',1) }}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.add',1) }} {{ trans_choice('general.announcement',1) }}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('announcement/store'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.name',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<input type="text" name="name" class="name form-control">
				</div>
            </div>
			<div class="form-group">
                {!! Form::label('current_date',trans_choice('general.date',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<input type="text" name="current_date" class="current_date date-picker form-control" value="{{date('Y-m-d')}}">
				</div>
            </div>
            <div class="form-group">
                {!! Form::label('announcement_type',trans_choice('general.announcement_type',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select class="announcement_type form-control select2" name="announcement_type">
						<option value="0">Select Announcement Type</option>
						@if(!empty($announcement_type))
							@foreach($announcement_type as $type)
								<option value="{{$type->id}}">{{$type->name}}</option>
							@endforeach
						@endif
					</select>
				</div>
            </div>
			<div class="form-group">
                {!! Form::label('announcement_date','Announcement Date',array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<input type="text" name="announcement_date" class="announcement_date date-picker form-control" placeholder="yyyy-mm-dd">
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('notes',null, array('class' => 'form-control', 'placeholder'=>"")) !!}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{ trans_choice('general.save',1) }}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->

<script></script>
@endsection