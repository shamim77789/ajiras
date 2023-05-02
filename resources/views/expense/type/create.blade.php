@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',1)}}</h3>

            <div class="box-tools pull-right">
                <i class="fa fa-refresh refresh" style="font-size: 18px;display: none;cursor: pointer;"></i>
            </div>
        </div>
        {!! Form::open(array('url' => url('expense/type/store'), 'method' => 'post', 'class' => 'form-horizontal expenseForm')) !!}
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-12">
                {!! Form::label('name',trans_choice('general.name',1),array('class'=>'control-label')) !!}
                    {!! Form::text('name',null, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <input type="hidden" name="expense_source" class="exsource">
            <input type="hidden" name="expense" class="expense">
            <div class="form-group">
                <div class="col-md-12">
                    <label class="control-label">Source of Expense</label>
                    <select name="source_expense" class="funds form-control select2" required>
                        <option value="">select expense source---</option>
                        <optgroup label="Contribution Type">
                            @foreach($ContributionType as $ctype)
                            <option value="{{ $ctype->id }}" source="contributiontype">{{ $ctype->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Fund">
                           @foreach($fund as $fund)
                            <option value="{{ $fund->id }}" source="fund">{{ $fund->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Other Income Type">
                           @foreach($otherIncome as $other)
                            <option value="{{ $other->id }}" source="otherincome">{{ $other->name }}</option>
                           @endforeach
                        </optgroup>
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
							<tr class="text-center">					
								<td>
									<input type="number" class="estimation_amount form-control" name="estimation_amount[]" value="0.00">
								</td>
								<td>
									<input type="number" class="year form-control" name="year[]" value="<?php echo date('Y');?>">
								</td>
								<td>
									<select class="quarter form-control" name="quarter[]">
										<option value="0">Select Quarter</option>
										<option value="1">January - March</option>
										<option value="2">April - June</option>
										<option value="3">July - September</option>
										<option value="4">October - December</option>
									</select>
								</td>
								<td>
									<span class="fa fa-plus-circle text-primary fa-2x cursor-pointer add_row"></span>
								</td>
							</tr>						
						</tbody>
					</table>
				</div>
			</div>			
            <!--  -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')
    <script>
        $(document).on("change", ".funds", function (evt) {
          var fieldValue  = $(this).val(); 
          $('.expense').val(fieldValue);
          $('.funds option').each(function() {
            if ( $(this).is(':selected') ) {
             
             var source = $(this).attr('source');

             $('.exsource').val(source);

            }
          });
        });
    </script>
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

