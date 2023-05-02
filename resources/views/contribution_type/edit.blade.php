@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.type',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}}  {{trans_choice('general.type',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('contribution/type/'.$contributiontype->id.'/update'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-12">
                    {!! Form::label('name',trans_choice('general.name',1),array('class'=>'control-label')) !!}
                    {!! Form::text('name',$contributiontype->name, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
			<div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('name',trans_choice('general.fund',1),array('class'=>'control-label')) !!}
					<select class="fund form-control" name="fund">
						<option value="0">Select Fund</option>
						@if(!empty($funds))
							@foreach($funds as $fund)
								<option value="{{$fund->id}}" {{$contributiontype->fund == $fund->id ? 'selected' : '' }}>
									{{$fund->name}}
								</option>
							@endforeach
						@endif
					</select>
                </div>
            </div>
			<div class="form-group">
				<div class="col-md-12">
					<table class="table">
						<thead>
							<th>Estimation Amount</th>
							<th>Year</th>
							<th>Quarter</th>
							<th>Action</th>
						</thead>
						<tbody class="table_body">
						@if(!empty($contribution_type_estimation))
							@foreach($contribution_type_estimation as $key => $estimation)
								<tr class="text-center">					
									<td>
										<input type="number" class="estimation_amount form-control" name="estimation_amount[]" value="{{$estimation->estimation_amount}}">
									</td>
									<td>
										<input type="number" class="year form-control" name="year[]" value="{{$estimation->year}}">
									</td>
									<td>
										<select class="quarter form-control" name="quarter[]">
											<option value="0">Select Quarter</option>
											<option value="1" {{$estimation->quarter == '1' ? 'selected' : ''}}>January - March</option>
											<option value="2" {{$estimation->quarter == '2' ? 'selected' : ''}}>April - June</option>
											<option value="3" {{$estimation->quarter == '3' ? 'selected' : ''}}>July - September</option>
											<option value="4" {{$estimation->quarter == '4' ? 'selected' : ''}}>October - December</option>
										</select>
									</td>
									<td>
										@if($key == '0')
											<span class="fa fa-plus-circle text-primary fa-2x cursor-pointer add_row"></span>
										@else
											<span class="fa fa-minus-circle text-danger fa-2x cursor-pointer remove_row"></span>
										@endif
									</td>
								</tr>
							@endforeach
						@endif
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-group">
                <div class="col-md-12"><p><label>Select Deduction</label></p></div>
                @foreach($deductions as $deduction)
                    <div class="col-md-3 col-sm-6">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="deductions[]" value="{{ $deduction->id }}"
                                       id="deductions" {{ in_array($deduction->id, $cdeduction) ? "checked" : "" }}> {{ $deduction->deduction_type }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
<script>
$(document).on('click','.add_row',function(){
var Html = '<tr class="text-center">';
	Html += '<td>';
	Html += '<input type="number" class="estimation_amount form-control" name="estimation_amount[]" value="0.00">';
	Html += '</td>';
	Html += '<td>';
	Html += '<input type="number" class="year form-control" name="year[]" value="<?php echo date('Y');?>">';
	Html += '</td>';
	Html += '<td>';
	Html += '<select class="quarter form-control" name="quarter[]">';
	Html += '<option value="0">Select Quarter</option>';
	Html += '<option value="1">January - March</option>';
	Html += '<option value="2">April - June</option>';
	Html += '<option value="3">July - September</option>';
	Html += '<option value="4">October - December</option>';
	Html += '</select>';
	Html += '</td>';
	Html += '<td>';
	Html += '<span class="fa fa-minus-circle text-danger fa-2x cursor-pointer remove_row"></span>';
	Html += '</td>';
	Html += '</tr>';

	$('.table_body').append(Html);
		
});
	
$(document).on('click','.remove_row',function(){
	$(this).parent().parent().remove();
});
</script>

@endsection