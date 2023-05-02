@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.soul_winning',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.soul_winning',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('soul_winning/store'), 'method' => 'post', 'id' => 'add_member_form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,null, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('first_name',trans_choice('general.first_name',1),array('class'=>'')) !!}
                {!! Form::text('first_name',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.first_name',1),'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('middle_name',trans_choice('general.middle_name',1),array('class'=>'')) !!}
                {!! Form::text('middle_name',null, array('class' => 'form-control', 'placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('last_name',trans_choice('general.last_name',1),array('class'=>'')) !!}
                {!! Form::text('last_name',null, array('class' => 'form-control', 'placeholder'=>'','required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('gender',trans_choice('general.gender',1),array('class'=>'')) !!}
                {!! Form::select('gender',array('male'=>trans_choice('general.male',1),'female'=>trans_choice('general.female',1),'unknown'=>trans_choice('general.unknown',1)),'unknown', array('class' => 'form-control',''=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('marital_status',trans_choice('general.marital_status',1),array('class'=>'')) !!}
                {!! Form::select('marital_status',array('single'=>trans_choice('general.single',1),'engaged'=>trans_choice('general.engaged',1),'married'=>trans_choice('general.married',1),'divorced'=>trans_choice('general.divorced',1),'widowed'=>trans_choice('general.widowed',1),'separated'=>trans_choice('general.separated',1),'unknown'=>trans_choice('general.unknown',1)),'unknown', array('class' => 'form-control',''=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('status',trans_choice('general.status',1),array('class'=>'')) !!}
                {!! Form::select('status',array('attender'=>trans_choice('general.attender',1),'visitor'=>trans_choice('general.visitor',1),'inactive'=>trans_choice('general.inactive',1),'unknown'=>trans_choice('general.unknown',1)),'unknown', array('class' => 'form-control',''=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('home_phone',trans_choice('general.home_phone',1),array('class'=>'')) !!}
                {!! Form::text('home_phone',null, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('mobile_phone',trans_choice('general.mobile_phone',1),array('class'=>'')) !!}
                {!! Form::text('mobile_phone',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.numbers_only',1))) !!}
            </div>
            <div class="form-group">
                {!! Form::label('work_phone',trans_choice('general.work_phone',1),array('class'=>'')) !!}
                {!! Form::text('work_phone',null, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email',trans_choice('general.email',1),array('class'=>'')) !!}
                {!! Form::text('email',null, array('class' => 'form-control', 'placeholder'=>trans_choice('general.email',1))) !!}
            </div>
            <div class="form-group">
                {!! Form::label('dob',trans_choice('general.dob',1),array('class'=>'')) !!}
                {!! Form::text('dob',null, array('class' => 'form-control date-picker', 'placeholder'=>"yyyy-mm-dd")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('address',trans_choice('general.address',1),array('class'=>'')) !!}
                {!! Form::textarea('address',null, array('class' => 'form-control', 'rows'=>"3")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('photo',trans_choice('general.photo',1),array('class'=>'')) !!}
                {!! Form::file('photo', array('class' => 'form-control', 'placeholder'=>"")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',null, array('class' => 'form-control', 'placeholder'=>"")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('files',trans_choice('general.file',2). ' '.trans_choice('general.borrower_file_types',2),array('class'=>'')) !!}
                {!! Form::file('files[]', array('class' => 'form-control', 'multiple'=>"")) !!}

                {{trans_choice('general.select_thirty_files',2)}}

            </div>


        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right"
                    id="add_member">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')
    <script>

    </script>
@endsection

