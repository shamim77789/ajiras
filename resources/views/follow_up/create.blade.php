@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.follow_up',1)}}
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.follow_up',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('follow_up/store'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,null, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                {!! Form::select('member_id',$members,null, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('follow_up_category_id',trans_choice('general.category',1),array('class'=>' control-label')) !!}
                {!! Form::select('follow_up_category_id',$categories,null, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('assigned_to_id',trans_choice('general.assigned_to',1),array('class'=>' control-label')) !!}
                {!! Form::select('assigned_to_id',$users,null, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',null, array('class' => 'form-control')) !!}
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
                <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')
    <script>

        $(document).ready(function (e) {

        })

    </script>
@endsection

