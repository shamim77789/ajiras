@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.member',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.member',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('member/'.$member->id.'/update'), 'method' => 'post', 'id' => 'add_member_form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
			<input type="hidden" name="transfer_id" value="{{(!empty($transfer) ? $transfer->id : '0')}}">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
				<select class="form-control select2" name="branch_id">
					<option value="0">Select Branch</option>
					@if(!empty($branches))
						@foreach($branches as $key => $branch)
							<option value="{{$key}}" {{ ( !empty($transfer) ? ( $transfer->transfer_to_branch == $key ? 'selected' : '') : ($key == $member->branch_id ? 'selected' : '') ) }}>{{$branch}}</option>
						@endforeach
					@endif
				</select>
            </div>
            <div class="form-group">
                {!! Form::label('first_name',trans_choice('general.first_name',1),array('class'=>'')) !!}
                {!! Form::text('first_name',$member->first_name, array('class' => 'form-control', 'placeholder'=>trans_choice('general.first_name',1),'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('middle_name',trans_choice('general.middle_name',1),array('class'=>'')) !!}
                {!! Form::text('middle_name',$member->middle_name, array('class' => 'form-control', 'placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('last_name',trans_choice('general.last_name',1),array('class'=>'')) !!}
                {!! Form::text('last_name',$member->last_name, array('class' => 'form-control', 'placeholder'=>'','required'=>'required')) !!}
            </div>
			<div class="form-group">
				<label>Member Number</label>
				<input type="text" name="member_number" class="member_number form-control" value="{{(!empty($transfer) ? '' : $member->member_number)}}">
			</div>			
			<div class="form-group">
                {!! Form::label('gender',trans_choice('general.gender',1),array('class'=>'')) !!}
                {!! Form::select('gender',array('male'=>trans_choice('general.male',1),'female'=>trans_choice('general.female',1),'unknown'=>trans_choice('general.unknown',1)),$member->gender, array('class' => 'form-control',''=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('marital_status',trans_choice('general.marital_status',1),array('class'=>'')) !!}
                {!! Form::select('marital_status',array('single'=>trans_choice('general.single',1),'engaged'=>trans_choice('general.engaged',1),'married'=>trans_choice('general.married',1),'divorced'=>trans_choice('general.divorced',1),'widowed'=>trans_choice('general.widowed',1),'separated'=>trans_choice('general.separated',1),'unknown'=>trans_choice('general.unknown',1)),$member->marital_status, array('class' => 'form-control',''=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('status',trans_choice('general.status',1),array('class'=>'')) !!}
                {!! Form::select('status',array('attender'=>trans_choice('general.attender',1),'visitor'=>trans_choice('general.visitor',1),'inactive'=>trans_choice('general.inactive',1),'unknown'=>trans_choice('general.unknown',1)),$member->status, array('class' => 'form-control',''=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('home_phone',trans_choice('general.home_phone',1),array('class'=>'')) !!}
                {!! Form::text('home_phone',$member->home_phone, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('mobile_phone',trans_choice('general.mobile_phone',1),array('class'=>'')) !!}
                {!! Form::text('mobile_phone',$member->mobile_phone, array('class' => 'form-control', 'placeholder'=>trans_choice('general.numbers_only',1))) !!}
            </div>
            <div class="form-group">
                {!! Form::label('work_phone',trans_choice('general.work_phone',1),array('class'=>'')) !!}
                {!! Form::text('work_phone',$member->work_phone, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email',trans_choice('general.email',1),array('class'=>'')) !!}
                {!! Form::text('email',$member->email, array('class' => 'form-control', 'placeholder'=>trans_choice('general.email',1))) !!}
            </div>

			<div class="form-group">
				<label>Group</label>
				<?php echo $group_checkbox;?>
			</div>
			<div class="form-group">
                {!! Form::label('dob',trans_choice('general.dob',1),array('class'=>'')) !!}
                {!! Form::text('dob',$member->dob, array('class' => 'form-control date-picker', 'placeholder'=>"yyyy-mm-dd")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('address',trans_choice('general.address',1),array('class'=>'')) !!}
                {!! Form::textarea('address',$member->address, array('class' => 'form-control', 'rows'=>"3")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('photo',trans_choice('general.photo',1),array('class'=>'')) !!}
                {!! Form::file('photo', array('class' => 'form-control', 'placeholder'=>"")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',$member->notes, array('class' => 'form-control', 'placeholder'=>"")) !!}
            </div>
            <div class="form-group">
                {!! Form::label('files',trans_choice('general.member',1).' '.trans_choice('general.file',2).'('.trans_choice('general.borrower_file_types',2).')',array('class'=>'')) !!}

                {!! Form::file('files[]', array('class' => 'form-control', 'multiple'=>"multiple")) !!}
                {{trans_choice('general.select_thirty_files',2)}}<br>
                @foreach(unserialize($member->files) as $key=>$value)
                    <span id="file_{{$key}}_span"><a href="{!!asset('uploads/'.$value)!!}"
                                                     target="_blank">{!!  $value!!}</a> <button value="{{$key}}"
                                                                                                id="{{$key}}"
                                                                                                onclick="delete_file(this)"
                                                                                                type="button"
                                                                                                class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i></button> </span><br>
                @endforeach

            </div>
            <div class="form-group">
                {!! Form::label('tags',trans_choice('general.assign',2). ' '.trans_choice('general.tag',2),array('class'=>'')) !!}
                <div id="jstree_div">
                    <ul>

                        <li data-jstree='{ "opened" : true }'
                            id="0">{{trans_choice('general.all',2)}} {{trans_choice('general.tag',2)}}
                            ({{\App\Models\MemberTag::count()}} {{trans_choice('general.people',2)}})
                            {!! \App\Helpers\GeneralHelper::createTreeView(0,$menus,$selected_tags) !!}
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
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="textfield")
                        <input type="text" class="form-control" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="date")
                        <input type="text" class="form-control date-picker" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="textarea")
                        <textarea class="form-control" name="{{$key->id}}"
                                  @if($key->required==1) required @endif>@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name}} @endif</textarea>
                    @endif
                    @if($key->field_type=="decimal")
                        <input type="text" class="form-control touchspin" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name}} @endif">
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
                                @if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()))
                                    @if(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name==$v)
                                        <option selected>{{$v}}</option>
                                    @else
                                        <option>{{$v}}</option>
                                    @endif
                                @else
                                    <option>{{$v}}</option>
                                @endif

                            @endforeach
                        </select>
                    @endif
                    @if($key->field_type=="radiobox")
                        @foreach(explode(',',$key->radio_box_values) as $v)
                            <div class="radio">
                                <label>
                                    @if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()))
                                        @if(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()->name==$v)
                                            <input type="radio" name="{{$key->id}}" id="{{$key->id}}" value="{{$v}}"
                                                   @if($key->required==1) required @endif checked>
                                        @else
                                            <input type="radio" name="{{$key->id}}" id="{{$key->id}}" value="{{$v}}"
                                                   @if($key->required==1) required @endif>
                                        @endif
                                    @else
                                        <input type="radio" name="{{$key->id}}" id="{{$key->id}}" value="{{$v}}"
                                               @if($key->required==1) required @endif>
                                    @endif

                                    <b>{{$v}}</b>
                                </label>
                            </div>
                        @endforeach
                    @endif
                    @if($key->field_type=="checkbox")
                        @if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$member->id)->where('category','members')->first()))
                            <?php $c = unserialize(\App\Models\CustomFieldMeta::where('custom_field_id',
                                $key->id)->where('parent_id', $member->id)->where('category',
                                'members')->first()->name); ?>

                            @foreach(explode(',',$key->checkbox_values) as $v)
                                <div class="checkbox">
                                    <label>
                                        @if(array_key_exists($v,$c))
                                            @if($c[$v]==$v)
                                                <input type="checkbox" name="{{$key->id}}[{{$v}}]" id="{{$key->id}}"
                                                       value="{{$v}}"
                                                       @if($key->required==1) required @endif checked>
                                            @else
                                                <input type="checkbox" name="{{$key->id}}[{{$v}}]" id="{{$key->id}}"
                                                       value="{{$v}}"
                                                       @if($key->required==1) required @endif>
                                            @endif
                                        @else
                                            <input type="checkbox" name="{{$key->id}}[{{$v}}]" id="{{$key->id}}"
                                                   value="{{$v}}"
                                                   @if($key->required==1) required @endif>
                                        @endif
                                        <b>{{$v}}</b>
                                    </label>
                                </div>
                            @endforeach
                        @else
                            @foreach(explode(',',$key->checkbox_values) as $v)
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="{{$key->id}}[{{$v}}]" id="{{$key->id}}"
                                               value="{{$v}}"
                                               @if($key->required==1) required @endif>
                                        <b>{{$v}}</b>
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    @endif

                </div>
            @endforeach
			<div class="custom-field">

			<p style="text-align:center; font-weight:bold;">
                <small><a href="{{url('custom_field/create')}}" target="_blank">Click here to add custom fields on
                        this page</a></small>
            </p>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right"
                    id="add_member">{{trans_choice('general.save',1)}}</button>
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

@endsection
@section('footer-scripts')
    <script>
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
        function delete_file(e) {
            var id = e.id;
            swal({
                title: 'Are you sure?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ok',
                cancelButtonText: 'Cancel'
            }).then(function () {
                $.ajax({
                    type: 'GET',
                    url: "{!!  url('member/'.$member->id) !!}/delete_file?id=" + id,
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
				Html += '<input type="hidden" name="custom_field_label[]" value="'+field_name+'">';
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

