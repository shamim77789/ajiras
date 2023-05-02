@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.follow_up',1)}}
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.follow_up',1)}}</h3>

            <div class="box-tools pull-right">
                {{$follow_up->user_id}}
            </div>
        </div>
        {!! Form::open(array('url' => url('follow_up/'.$follow_up->id.'/update'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,$follow_up->member_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                {!! Form::select('member_id',$members,$follow_up->member_id, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('follow_up_category_id',trans_choice('general.category',1),array('class'=>' control-label')) !!}
                {!! Form::select('follow_up_category_id',$categories,$follow_up->follow_up_category_id, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('assigned_to_id',trans_choice('general.assigned_to',1),array('class'=>' control-label')) !!}
                {!! Form::select('assigned_to_id',$users,$follow_up->assigned_to_id, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('due_date',trans_choice('general.due',1).' '.trans_choice('general.date',1),array('class'=>'')) !!}
                {!! Form::text('due_date',$follow_up->due_date, array('class' => 'form-control','required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',$follow_up->notes, array('class' => 'form-control')) !!}
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


        function delete_file(e) {
            var id = e.id;
            swal({
                title: '{{trans_choice('general.are_you_sure',1)}}',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{trans_choice('general.ok',1)}}',
                cancelButtonText: '{{trans_choice('general.cancel',1)}}'
            }).then(function () {
                $.ajax({
                    type: 'GET',
                    url: "{!!  url('follow_up/'.$follow_up->id) !!}/delete_file?id=" + id,
                    success: function (data) {
                        $("#file_" + id + "_span").remove();
                        swal({
                            title: 'Deleted',
                            text: 'File successfully deleted',
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok',
                            timer: 2000
                        })
                    }
                });
            })

        }
    </script>
@endsection

