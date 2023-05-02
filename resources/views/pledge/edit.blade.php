@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.pledge',1)}}
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.pledge',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('pledge/'.$pledge->id.'/update'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <p class="bg-navy disabled color-palette">{{trans_choice('general.required',1)}} {{trans_choice('general.field',2)}}</p>
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,$pledge->branch_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}

                {!! Form::select('member_id',$members,$pledge->member_id, array('class' => 'form-control','required'=>'required')) !!}

            </div>
			<div class="checkbox icheck">
				<label>
					<input type="checkbox" name="member_type" value="1"
						   id="member_type" {{$pledge->member_type == '0' ? 'checked' : ''}}> {{ trans('general.anonymous') }}
				</label>
			</div>
			<div class="form-group">
                {!! Form::label('campaign_id',trans_choice('general.campaign',1),array('class'=>' control-label')) !!}
                {!! Form::select('campaign_id',$campaigns,$pledge->campaign_id, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('amount',trans_choice('general.expense',1).' '.trans_choice('general.amount',1),array('class'=>'')) !!}
                {!! Form::text('amount',$pledge->amount, array('class' => 'form-control touchspin', 'placeholder'=>"",'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('date',trans_choice('general.date',1),array('class'=>'')) !!}
                {!! Form::text('date',$pledge->date, array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
            </div>
            <p class="bg-navy disabled color-palette">{{trans_choice('general.optional',1)}} {{trans_choice('general.field',2)}}</p>

            <div class="form-group">
                {!! Form::label('Recurring',trans_choice('general.recurring',1),array('class'=>'active')) !!}
                {!! Form::select('recurring', array('1'=>trans_choice('general.yes',1),'0'=>trans_choice('general.no',1)),$pledge->recurring, array('class' => 'form-control','id'=>'recurring')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('batch',trans_choice('general.batch',1),array('class'=>'active')) !!}
				<select class="select2 form-control" name="batches">
					<option value="0">Select Batches</option>
					@if(!empty($batches))
						@foreach($batches as $batch)
							<option value="{{$batch->id}}" {{$pledge->batches == $batch->id ? 'selected' : ''}}>{{$batch->name}}</option>
						@endforeach
					@endif
				</select>
            </div>
			<div id="recur">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_frequency',trans_choice('general.recur_frequency',1),array('class'=>'')) !!}
                                {!! Form::number('recur_frequency',$pledge->recur_frequency, array('class' => 'form-control','id'=>'recurF')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_type',trans_choice('general.recur_type',1),array('class'=>'active')) !!}
                                {!! Form::select('recur_type', array('day'=>'Day(s)','week'=>'Week(s)','month'=>'Month(s)','year'=>'Year(s)'),$pledge->recur_type, array('class' => 'form-control','id'=>'recurT')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_start_date',trans_choice('general.recur_starts',1),array('class'=>'')) !!}
                                {!! Form::text('recur_start_date',$pledge->recur_start_date, array('class' => 'form-control date-picker','id'=>'recur_start_date')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_end_date',trans_choice('general.recur_ends',1),array('class'=>'')) !!}
                                {!! Form::text('recur_end_date',$pledge->recur_end_date, array('class' => 'form-control date-picker','id'=>'recur_end_date')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',$pledge->notes, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group clearfix">
                <hr>
				<?php echo $group_checkbox;?>
			</div>
            <p class="bg-navy disabled color-palette clearfix">{{trans_choice('general.custom_field',2)}}</p>
            @foreach($custom_fields as $key)

                <div class="form-group">
                    {!! Form::label($key->id,$key->name,array('class'=>'')) !!}
                    @if($key->field_type=="number")
                        <input type="number" class="form-control" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="textfield")
                        <input type="text" class="form-control" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="date")
                        <input type="text" class="form-control date-picker" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="textarea")
                        <textarea class="form-control" name="{{$key->id}}"
                                  @if($key->required==1) required @endif>@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first()->name}} @endif</textarea>
                    @endif
                    @if($key->field_type=="decimal")
                        <input type="text" class="form-control touchspin" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$pledge->id)->where('category','expenses')->first()->name}} @endif">
                    @endif
                </div>
            @endforeach
            <p style="text-align:center; font-weight:bold;">
                <small><a href="{{url('custom_field/create')}}" target="_blank">Click here to add custom fields on
                        this page</a></small>
            </p>

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
            if ($('#recurring').val() == '1') {
                $('#recur').show();
                $('#recurT').attr('required', 'required');
                $('#recur_start_date').attr('required', 'required');
                $('#recurF').attr('required', 'required');
            } else {
                $('#recur').hide();
                $('#recurT').removeAttr('required');
                $('#recur_start_date').removeAttr('required');
                $('#recurF').removeAttr('required');
            }
            $('#recurring').change(function () {
                if ($('#recurring').val() == '1') {
                    $('#recur').show();
                    $('#recurT').attr('required', 'required');
                    $('#recurF').attr('required', 'required');
                    $('#recur_start_date').attr('required', 'required');
                } else {
                    $('#recur').hide();
                    $('#recurT').removeAttr('required');
                    $('#recur_start_date').removeAttr('required');
                    $('#recurF').removeAttr('required');
                }
            })
        })
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
                    url: "{!!  url('pledge/'.$pledge->id) !!}/delete_file?id=" + id,
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
		$(document).on('change','.member_id',function(){
			var member_id = $(this).val();
			$.ajax({
              url: "{{ url('/pledge/get_member_groups') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 member_id: member_id
              },
              success: function(result)
			  {
				  $("input[type=checkbox]").prop("checked", false);
				  var counter = result.length;
				  for(var i = 0; i < counter; i++)
				  {
					  $("input[type=checkbox][value="+result[i]['group_id']+"]").prop("checked",true);
				  }

			  }
                      
            });
		});


	</script>
@endsection

