@extends('layouts.master')
@section('title')
    {{ trans_choice('general.edit',1) }} {{ trans_choice('general.contribution',1) }}
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.edit',1) }} {{ trans_choice('general.contribution',1) }}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('contribution/'.$contribution->id.'/update'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <p class="bg-navy disabled color-palette">{{ trans_choice('general.required',1) }} {{ trans_choice('general.field',2) }}</p>
            @if(isset($_REQUEST['return_url']))
                <input type="hidden" name="return_url" value="{{$_REQUEST['return_url']}}">
            @endif
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}

                {!! Form::select('branch_id',$branches,$contribution->branch_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                <div id="memberDetails">
                    {!! Form::select('member_id',$members,$contribution->member_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'member_id')) !!}
                </div>
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="member_type" value="1"
                               id="member_type"
                               @if($contribution->member_type==0) checked @endif> {{ trans('general.anonymous') }}
                    </label>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('contribution_batch_id',trans_choice('general.batch',1),array('class'=>' control-label')) !!}

                {!! Form::select('contribution_batch_id',$batches,$contribution->contribution_batch_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('contribution_type_id',trans_choice('general.type',1),array('class'=>' control-label')) !!}
                {!! Form::select('contribution_type_id',$contribution_types,$contribution->contribution_type_id, array('class' => 'form-control select2','placeholder'=>'','id'=>'contribution_type_id')) !!}
            </div>

            <div class="form-group">
                {!! Form::label('amount',trans_choice('general.income',1).' '.trans_choice('general.amount',1),array('class'=>'')) !!}
                {!! Form::text('amount',$contribution->amount, array('class' => 'form-control touchspin', 'placeholder'=>"",'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('date',trans_choice('general.date',1),array('class'=>'')) !!}
                {!! Form::text('date',$contribution->date, array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('fund_id',trans_choice('general.fund',1),array('class'=>' control-label')) !!}

                {!! Form::select('fund_id',$funds,$contribution->fund_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'fund_id')) !!}

            </div>
            <div class="form-group">
                {!! Form::label('payment_method_id',trans_choice('general.payment',1).' '.trans_choice('general.method',1),array('class'=>' control-label')) !!}

                {!! Form::select('payment_method_id',$payment_methods,$contribution->payment_method_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'payment_method_id')) !!}

            </div>


            <p class="bg-navy disabled color-palette">{{ trans_choice('general.optional',1) }} {{ trans_choice('general.field',2) }}</p>

            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.description',1),array('class'=>'')) !!}
                {!! Form::textarea('notes',$contribution->notes, array('class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('files',trans_choice('general.file',2).'('.trans_choice('general.borrower_file_types',1).')',array('class'=>'')) !!}
                {!! Form::file('files[]', array('class' => 'form-control', 'multiple'=>"",'rows'=>'3')) !!}
                <div class="col-sm-12">{{trans_choice('general.select_thirty_files',1)}}<br>
                    @foreach(unserialize($contribution->files) as $key=>$value)
                        <span id="file_{{$key}}_span"><a href="{!!asset('uploads/'.$value)!!}"
                                                         target="_blank">{!!  $value!!}</a> <button value="{{$key}}"
                                                                                                    id="{{$key}}"
                                                                                                    onclick="delete_file(this)"
                                                                                                    type="button"
                                                                                                    class="btn btn-danger btn-xs">
                                <i class="fa fa-trash"></i></button> </span><br>
                    @endforeach
                </div>
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
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="textfield")
                        <input type="text" class="form-control" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="date")
                        <input type="text" class="form-control date-picker" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first()->name}} @endif">
                    @endif
                    @if($key->field_type=="textarea")
                        <textarea class="form-control" name="{{$key->id}}"
                                  @if($key->required==1) required @endif>@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','expenses')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first()->name}} @endif</textarea>
                    @endif
                    @if($key->field_type=="decimal")
                        <input type="text" class="form-control touchspin" name="{{$key->id}}"
                               @if($key->required==1) required
                               @endif value="@if(!empty(\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first())){{\App\Models\CustomFieldMeta::where('custom_field_id',$key->id)->where('parent_id',$contribution->id)->where('category','contributions')->first()->name}} @endif">
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
        if ($("#member_type").attr('checked')) {
            $("#member_id").removeAttr('required');
            $("#memberDetails").hide();
        } else {
            $("#member_id").attr('required', 'required');
            $("#memberDetails").show();
        }
        $("#member_type").on('ifChecked', function (e) {
            $("#member_id").removeAttr('required');
            $("#memberDetails").hide();

        });
        $("#member_type").on('ifUnchecked', function (e) {
            $("#member_id").attr('required', 'required');
            $("#memberDetails").show();
        });

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
                    url: "{!!  url('contribution/'.$contribution->id) !!}/delete_file?id=" + id,
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
              url: "{{ url('/contribution/get_member_groups') }}",
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

