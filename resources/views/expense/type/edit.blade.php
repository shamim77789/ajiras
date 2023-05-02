@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.expense',1)}} {{trans_choice('general.type',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('expense/type/'.$expense_type->id.'/update'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="form-group">
                <div class="col-sm-12">
                    {!! Form::label('name',trans_choice('general.name',1),array('class'=>' control-label')) !!}
                    {!! Form::text('name',$expense_type->name, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
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
                            <option value="{{ $ctype->id }}" source="contributiontype" {{ ($ctype->id == $expense_type->contribution_type_id) ? "selected" : "" }}>{{ $ctype->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Fund">
                           @foreach($fund as $fund)
                            <option value="{{ $fund->id }}" source="fund" {{ ($fund->id == $expense_type->fund_id) ? "selected" : "" }}>{{ $fund->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Other Income Type">
                           @foreach($otherIncome as $other)
                            <option value="{{ $other->id }}" source="otherincome" {{ ($other->id == $expense_type->otherincome_type_id) ? "selected" : "" }}>{{ $other->name }}</option>
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
						@if(!empty($expense_types_estimation))
							@foreach($expense_types_estimation as $key => $estimation)
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
						@else
								<tr class="text-center">					
									<td>
										<input type="number" class="estimation_amount form-control" name="estimation_amount[]">
									</td>
									<td>
										<input type="number" class="year form-control" name="year[]">
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

						@endif
						</tbody>
					</table>
				</div>
			</div>
		
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
        $(document).ready(function(){
            $('.funds option').each(function() {
                if ( $(this).is(':selected') ) {

                var fieldValue  = $(this).val(); 
                $('.expense').val(fieldValue);
                 
                 var source = $(this).attr('source');

                 $('.exsource').val(source);

                }
            });
        });
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
@endsection

