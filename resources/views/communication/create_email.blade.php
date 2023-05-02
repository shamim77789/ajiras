@extends('layouts.master')
@section('title'){{trans_choice('general.send',1)}} {{trans_choice('general.email',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.send',1)}} {{trans_choice('general.email',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('communication/email/store'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <p>In your email you can use any of the following tags:
                {firstName}, {lastName}, {address}, {mobilePhone},
                {homePhone}</p>

            <div class="form-group">
                {!! Form::label('send_to',trans_choice('general.to',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::select('send_to',$members, $selected,array('class' => 'form-control select2', 'required'=>"")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('group',trans_choice('general.group',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select class="groups select2 form-control" name="groups">
						<option value="0">Select Group</option>
						@if(!empty($groups))
							@foreach($groups as $group)
							<option value="{{$group->id}}">{{$group->group_name}}</option>
							@endforeach
						@endif
					</select>
				</div>
            </div>
			<div class="form-group">
                {!! Form::label('subject',trans_choice('general.subject',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('subject',null, array('class' => 'form-control', 'required'=>"")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('message',trans_choice('general.message',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('message',null, array('class' => 'form-control tinymce', 'placeholder'=>"")) !!}
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

