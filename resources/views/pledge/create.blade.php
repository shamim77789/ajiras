@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.pledge',1)}}
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.pledge',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('pledge/store'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,null, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                {!! Form::select('member_id',$members,null, array('class' => 'form-control member_id select2','required'=>'required','placeholder'=>'')) !!}
            </div>
			<div class="checkbox icheck">
				<label>
					<input type="checkbox" name="member_type" value="1"
						   id="member_type"> {{ trans('general.anonymous') }}
				</label>
			</div>			
            <div class="form-group">
                {!! Form::label('campaign_id',trans_choice('general.campaign',1),array('class'=>' control-label')) !!}
                {!! Form::select('campaign_id',$campaigns,null, array('class' => 'form-control select2','required'=>'required','placeholder'=>'')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('amount',trans_choice('general.amount',1),array('class'=>'')) !!}
                {!! Form::text('amount',null, array('class' => 'form-control touchspin', 'placeholder'=>"",'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('date',trans_choice('general.date',1),array('class'=>'')) !!}
                {!! Form::text('date',date("Y-m-d"), array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('recurring',trans_choice('general.recurring',1),array('class'=>'active')) !!}
                {!! Form::select('recurring', array('1'=>trans_choice('general.yes',1),'0'=>trans_choice('general.no',1)),0, array('class' => 'form-control','id'=>'recurring')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('recurring',trans_choice('general.batch',1),array('class'=>'active')) !!}
				<select class="select2 form-control" name="batches">
					<option value="0">Select Batches</option>
					@if(!empty($batches))
						@foreach($batches as $batch)
							<option value="{{$batch->id}}">{{$batch->name}}</option>
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
                                {!! Form::number('recur_frequency',1, array('class' => 'form-control','id'=>'recurF')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_type',trans_choice('general.recur_type',1),array('class'=>'active')) !!}
                                {!! Form::select('recur_type', array('day'=>trans_choice('general.day',1).'(s)','week'=>trans_choice('general.week',1).'(s)','month'=>trans_choice('general.month',1).'(s)','year'=>trans_choice('general.year',1).'(s)'),'month', array('class' => 'form-control','id'=>'recurT')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_start_date',trans_choice('general.recur_starts',1),array('class'=>'')) !!}
                                {!! Form::text('recur_start_date',date("Y-m-d"), array('class' => 'form-control date-picker','id'=>'recur_start_date')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                {!! Form::label('recur_end_date',trans_choice('general.recur_ends',1),array('class'=>'')) !!}
                                {!! Form::text('recur_end_date',null, array('class' => 'form-control date-picker','id'=>'recur_end_date')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',null, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <hr>
				<?php echo $group_checkbox;?>
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
        });

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
				  console.log(result);
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

