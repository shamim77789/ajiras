@extends('layouts.master')
@section('title'){{trans_choice('general.add',1)}} {{trans_choice('general.pension',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.pension',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('pension/'.$pension->id.'/update'), 'method' => 'post', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <input type="hidden" name="template_id" value="{{$template->id}}">

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' control-label')) !!}
                {!! Form::select('branch_id',$branches,$pension->branch_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('user_id',trans_choice('general.staff',1),array('class'=>'')) !!}
                {!! Form::select('user_id',$user,$pension->user_id, array('class' => 'form-control select2','id'=>'user_id','placeholder'=>'Select')) !!}
            </div>
            <div class="form-group">
                {!! Form::label('expense_type','Expense Type',array('class'=>'')) !!}
				<select class="form-control select2 expense_type" name="expense_type">
					<option value="0">Select Expense Type</option>
					@if(!empty($expense_type))
						@foreach($expense_type as $type)
							<option value="{{$type->id}}" {{$pension->expense_type == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
						@endforeach
					@endif
				</select>
            </div>

            <div class="">
                <table width="100%">
                    <tbody>
                    <tr>
                        <td style="padding-bottom:10px;">
                            <table width="100%" class="borderOk">
                                <tbody>
                                <tr>
                                    <td style="vertical-align: top;" width="50%">

                                        <table width="100%" id="payslip_employee_header">
                                            <tbody>
                                            <tr>
                                                <td width="50%"
                                                    class="cell_format">{{trans_choice('general.employee',1)}} {{trans_choice('general.name',1)}}</td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        {!! Form::text('employee_name',$pension->employee_name, array('class' => 'form-control', 'id'=>"employee_name",'required'=>"required")) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            @foreach($top_left as $key)
                                                <tr>
                                                    <td width="50%" class="cell_format">{{$key->name}}</td>
                                                    <td width="50%" class="cell_format">
                                                        <div class="margin text-bold">
                                                            <input type="text" name="{{$key->id}}"
                                                                   value="@if(\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()) {{\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()->value}} @endif"
                                                                   class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>

                                    <td style="vertical-align: top" width="50%">

                                        <table width="100%" id="pay_period_and_salary">

                                            <tbody>
                                            <tr>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin">
                                                        <b>{{trans_choice('general.pension',1)}} {{trans_choice('general.date',1)}}</b>
                                                    </div>
                                                </td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        {!! Form::text('date',$pension->date, array('class' => 'form-control date-picker', 'required'=>"required")) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%"
                                                    class="cell_format">{{trans_choice('general.cogregation',1)}} {{trans_choice('general.name',1)}}</td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        {!! Form::text('business_name',$pension->business_name, array('class' => 'form-control', 'id'=>"business_name",'required'=>"required")) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            @foreach($top_right as $key)
                                                <tr>
                                                    <td width="50%" class="cell_format">{{$key->name}}</td>
                                                    <td width="50%" class="cell_format">
                                                        <div class="margin text-bold">
                                                            <input type="text" name="{{$key->id}}"
                                                                   value="@if(\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()) {{\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()->value}} @endif"
                                                                   class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <!--Pay Period and Salary-->
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table width="100%" class="borderOk">
                                <tbody>
                                <tr>
                                    <td style="vertical-align: top" width="50%" class="borderRight">

                                        <table width="100%" id="hours_and_earnings">
                                            <tbody>
                                            <tr>
                                                <td width="50%" class="bg-navy">
                                                    <b>{{trans_choice('general.description',1)}}</b></td>
                                                <td width="50%" class="bg-navy">
                                                    <b>{{trans_choice('general.amount',1)}}</b></td>
                                            </tr>
                                            <?php
                                            $count = 0;
                                            foreach($bottom_left as $key){
                                            ?>
                                            <tr>
                                                <td width="50%" class="cell_format">{{$key->name}}</td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        <input type="text" onkeyup="refresh_totals()"
                                                               name="{{$key->id}}"
                                                               value="@if(\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()) {{\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()->value}} @endif"
                                                               class="form-control" id="bottom_left{{$count}}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <!--Hours and Earnings-->
                                    </td>

                                    <td width="50%" valign="top">
                                        <table width="100%" id="pre_tax_deductions">
                                            <tbody>
                                            <tr>
                                                <td width="50%" class="bg-navy">
                                                    <b>{{trans_choice('general.description',1)}}</b></td>
                                                <td width="50%" class="bg-navy">
                                                    <b>{{trans_choice('general.amount',1)}}</b></td>
                                            </tr>
                                            <?php
                                            $count = 0;
                                            foreach($bottom_right as $key){
                                            ?>
                                            <tr>
                                                <td width="50%" class="cell_format">{{$key->name}}</td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        <input type="text" onkeyup="refresh_totals()"
                                                               name="{{$key->id}}"
                                                               value="@if(\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()) {{\App\Models\PensionMeta::where('pension_template_meta_id',$key->id)->where('pension_id',$pension->id)->first()->value}} @endif"
                                                               class="form-control" id="bottom_right{{$count}}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <!--Pre-Tax Deductions-->
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%" class="bg-gray">
                                        <table width="100%" id="gross_pay">
                                            <tbody>
                                            <tr>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin">
                                                        <b>{{trans_choice('general.total',1)}} {{trans_choice('general.pay',1)}}</b>
                                                    </div>
                                                </td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        {!! Form::text('total_pay',\App\Helpers\GeneralHelper::single_pension_total_pay($pension->id), array('class' => 'form-control', 'readonly'=>"",'id'=>'total_pay')) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                    <td width="50%" class="bg-gray">

                                        <table width="100%" id="gross_pay">
                                            <tbody>
                                            <tr>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin">
                                                        <b>{{trans_choice('general.total',1)}} {{trans_choice('general.deduction',2)}}</b>
                                                    </div>
                                                </td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        {!! Form::text('total_deductions',\App\Helpers\GeneralHelper::single_pension_total_deductions($pension->id), array('class' => 'form-control', 'readonly'=>"",'id'=>'total_deductions')) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="50%">
                                        <br>
                                    </td>
                                    <td width="50%" class="bg-gray">
                                        <table width="100%" id="gross_pay">
                                            <tbody>
                                            <tr>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin">
                                                        <b>{{trans_choice('general.net',1)}} {{trans_choice('general.pay',1)}}</b>
                                                    </div>
                                                </td>
                                                <td width="50%" class="cell_format">
                                                    <div class="margin text-bold">
                                                        {!! Form::text('net_pay',(\App\Helpers\GeneralHelper::single_pension_total_pay($pension->id)-\App\Helpers\GeneralHelper::single_pension_total_deductions($pension->id)), array('class' => 'form-control', 'readonly'=>"",'id'=>'net_pay')) !!}
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;">
                            <table width="100%" class="borderOk" id="net_pay_distribution">
                                <tbody>
                                <tr>
                                    <td colspan="5" class="bg-navy">
                                        <b>{{trans_choice('general.net_pay_distribution',1)}}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" class="cell_format">
                                        <div class="margin">
                                            <b>{{trans_choice('general.payment',1)}} {{trans_choice('general.method',1)}}</b>
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin">
                                            <b>{{trans_choice('general.bank',1)}} {{trans_choice('general.name',1)}}</b>
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin">
                                            <b>{{trans_choice('general.account',1)}} {{trans_choice('general.number',1)}}</b>
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin">
                                            <b>{{trans_choice('general.description',1)}}</b>
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin">
                                            <b>{{trans_choice('general.paid',1)}} {{trans_choice('general.amount',1)}}</b>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="20%" class="cell_format">
                                        <div class="margin text-bold">
                                            {!! Form::text('payment_method',$pension->payment_method, array('class' => 'form-control', 'required'=>"")) !!}
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin text-bold">
                                            {!! Form::text('bank_name',$pension->bank_name, array('class' => 'form-control', ''=>"")) !!}
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin text-bold">
                                            {!! Form::text('account_number',$pension->account_number, array('class' => 'form-control', ''=>"")) !!}
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin text-bold">
                                            {!! Form::text('description',$pension->description, array('class' => 'form-control', ''=>"")) !!}
                                        </div>
                                    </td>
                                    <td width="20%" class="cell_format">
                                        <div class="margin text-bold">
                                            {!! Form::text('paid_amount',$pension->paid_amount, array('class' => 'form-control', 'id'=>"paid_amount")) !!}

                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!--Net Pay Distribution-->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" class="borderOk" style="margin-top:10px" id="messages">
                                <tbody>
                                <tr>
                                    <td width="100%" class="cell_format">
                                        <div class="margin"><b>{{trans_choice('general.comment',2)}}</b></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="100%" class="cell_format">
                                        <div class="margin text-bold">
                                            {!! Form::textarea('comments',$pension->comments, array('class' => 'form-control', ''=>"")) !!}
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!--Messages-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    {!! Form::label('Recurring',null,array('class'=>'active')) !!}
                    {!! Form::select('recurring', array('1'=>trans_choice('general.yes',1),'0'=>trans_choice('general.no',1)),$pension->recurring, array('class' => 'form-control','id'=>'recurring')) !!}
                </div>
                <div id="recur">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label(trans_choice('general.recur_frequency',1),null,array('class'=>'')) !!}
                                    {!! Form::number('recur_frequency',$pension->recur_frequency, array('class' => 'form-control','id'=>'recurF')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label(trans_choice('general.recur_type',1),null,array('class'=>'active')) !!}
                                    {!! Form::select('recur_type', array('day'=>'Day(s)','week'=>'Week(s)','month'=>'Month(s)','year'=>'Year(s)'),$pension->recur_type, array('class' => 'form-control','id'=>'recurT')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label(trans_choice('general.recur_starts',1),null,array('class'=>'')) !!}
                                    {!! Form::text('recur_start_date',$pension->recur_start_date, array('class' => 'form-control date-picker','id'=>'recur_start_date')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label(trans_choice('general.recur_ends',1),null,array('class'=>'')) !!}
                                    {!! Form::text('recur_end_date',$pension->recur_end_date, array('class' => 'form-control date-picker','id'=>'recur_end_date')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection

@section('footer-scripts')

    <script>
        $('#user_id').change(function (e) {
            $.ajax({
                type: 'GET',
                url: '{!! url('pension/getUser') !!}/' + $('#user_id').val(),
                success: function (data) {
                    $('#employee_name').val(data);
                }
            });
        })
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
        function refresh_totals(e) {
            var totalPay = 0;
            var totalDeductions = 0;
            var totalPaid = 0;
            var netPay = 0;
            for (var i = 0; i < '{{count($bottom_left)}}'; i++) {
                var pay = document.getElementById("bottom_left" + i).value;
                if (pay == "")
                    pay = 0;
                totalPay = parseFloat(totalPay) + parseFloat(pay);
            }
            for (var i = 0; i < '{{count($bottom_right)}}'; i++) {
                var deduction = document.getElementById("bottom_right" + i).value;
                if (deduction == "")
                    deduction = 0;
                totalDeductions = parseFloat(totalDeductions) + parseFloat(deduction);
            }

            document.getElementById("total_pay").value = totalPay;
            document.getElementById("total_deductions").value = totalDeductions;
            document.getElementById("net_pay").value = totalPay - totalDeductions;
            document.getElementById("paid_amount").value = totalPay - totalDeductions;
        }
    </script>
@endsection