@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}
@endsection
@section('content')
<style>
	.ins_class
	{
		position: absolute; 
		top: -20%; 
		left: -20%; 
		display: block; 
		width: 140%; 
		height: 140%; 
		margin: 0px; 
		padding: 0px; 
		background: rgb(255, 255, 255); 
		border: 0px; 
		opacity: 0;
	}
	.checkbox_class{
		position: absolute; 
		top: -20%; 
		left: -20%; 
		display: block; 
		width: 140%; 
		height: 140%; 
		margin: 0px; 
		padding: 0px; 
		background: rgb(255, 255, 255); 
		border: 0px; 
		opacity: 0;
	}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('member/store'), 'method' => 'post', 'id' => 'add_member_form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,null, array('class' => 'form-control select2','placeholder'=>'Select Branch','required'=>'required','id'=>'contribution_batch_id')) !!}
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
				<label>Member Number</label>
				<input type="text" name="member_number" class="member_number form-control">
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
				<label>Group</label>
				<?php echo $group_checkbox; ?>
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
            <div class="form-group">
                {!! Form::label('tags',trans_choice('general.assign',2). ' '.trans_choice('general.tag',2),array('class'=>'')) !!}

                <div id="jstree_div">
                    <ul>

                        <li data-jstree='{ "opened" : true }'
                            id="0">{{trans_choice('general.all',2)}} {{trans_choice('general.tag',2)}}
                            ({{\App\Models\MemberTag::count()}} {{trans_choice('general.people',2)}})
                            {!! \App\Helpers\GeneralHelper::createTreeView(0,$menus) !!}
                        </li>
                    </ul>
                </div>
                <input type="hidden" name="tags" id="tags" value=""/>
            </div>

            <p class="bg-navy disabled color-palette">{{trans_choice('general.custom_field',2)}}</p>

            @foreach($custom_fields as $key)

                <div class="form-group">
                    {!! Form::label($key->id,$key->name,array('class'=>'')) !!}
                    @if($key->field_type=="number")
                        <input type="number" class="form-control" name="{{$key->id}}"
                               @if($key->required==1) required @endif>
                    @endif
                    @if($key->field_type=="textfield")
                        <input type="text" class="form-control" name="{{$key->id}}"
                               @if($key->required==1) required @endif>
                    @endif
                    @if($key->field_type=="date")
                        <input type="text" class="form-control date-picker" name="{{$key->id}}"
                               @if($key->required==1) required @endif>
                    @endif
                    @if($key->field_type=="textarea")
                        <textarea class="form-control" name="{{$key->id}}"
                                  @if($key->required==1) required @endif></textarea>
                    @endif
                    @if($key->field_type=="decimal")
                        <input type="text" class="form-control touchspin" name="{{$key->id}}"
                               @if($key->required==1) required @endif>
                    @endif
                    @if($key->field_type=="select")
                        <select class="form-control touchspin" name="{{$key->id}}"
                                @if($key->required==1) required @endif>
                            @if($key->required!=1)
                                <option value=""></option>
                            @else
                                <option value="" disabled selected>Select...</option>
                            @endif
                            @foreach(explode(',',$key->select_values) as $v)
                                <option>{{$v}}</option>
                            @endforeach
                        </select>
                    @endif
                    @if($key->field_type=="radiobox")
                        @foreach(explode(',',$key->radio_box_values) as $v)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="{{$key->id}}" id="{{$key->id}}" value="{{$v}}"
                                           @if($key->required==1) required @endif>
                                    <b>{{$v}}</b>
                                </label>
                            </div>
                        @endforeach
                    @endif
                    @if($key->field_type=="checkbox")
                        @foreach(explode(',',$key->checkbox_values) as $v)
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="{{$key->id}}[{{$v}}]" id="{{$key->id}}" value="{{$v}}"
                                           @if($key->required==1) required @endif>
                                    <b>{{$v}}</b>
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
		<!--	<div class="custom-field"></div>
			   <p><span class="text-primary cursor-pointer" style="cursor:pointer;" data-toggle="modal" data-target="#exampleModalCenter">
				Click Here To Add Custom Fileds On This Page
				</span>
			</p> -->
            <p style="text-align:center; font-weight:bold;">
                <small><a href="{{url('custom_field/create')}}" target="_blank">Click here to add custom fields on
                        this page</a></small>
            </p>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right" id="add_member">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
<!-- /.box -->
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Custom Field</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <div class="row">
			  <div class="col-lg-12 col-sm-12 col-md-12">
				  <label>Field Name</label>
				  <input type="text" class="custom_field_name form-control" name="custom_field_name" placeholder="Enter Field Name">
			  </div>
			  <div class="col-lg-12 col-sm-12 col-md-12 mt-2" style="margin-top: 10px;">
				  <button type="button" class="btn btn-primary create_field">Save changes</button>
			  </div>
		  </div>
		</div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@section('footer-scripts')
    <script>
		$(document).ready(function() {
			$('.js-example-basic-multiple').select2();
		});
		
		$('#jstree_div').jstree({
            "core": {
                "themes": {
                    "responsive": true
                },
                // so that create works
                "check_callback": true,
            },
            "plugins": ["checkbox", 'wholerow'],
        });
        $('#add_member').click(function (e) {
            e.preventDefault();
            $('#tags').val($('#jstree_div').jstree("get_selected"))
            $('#add_member_form').submit();
        })
		/*Create Field*/
		$(document).on('click','.create_field',function(){
			var elem = $(this);
			var field_name = elem.parent().parent().find('.custom_field_name').val();
			var Html = '';
			console.log(field_name);
			if(field_name != '')
			{
				Html += '<div class="row">';
				Html += '<div class="col-lg-12 col-sm-12 col-md-12">';				
				Html += '<label>'+field_name+'</label>';
				Html += '<input type="hidden" name="custom_field_label[]">';
				Html += '<input type="text" name="custom_field[]" class="form-control custom_field">';
				Html += '</div>';
				Html += '</div>';				
				Html += '</div>';				
			}
			$('.close').click();
			elem.parent().parent().find('.custom_field_name').val('');
			$('.custom-field').append(Html);
			
		});
		
    </script>
@endsection

