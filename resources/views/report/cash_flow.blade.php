@extends('layouts.master')
@section('title')
    {{trans_choice('general.cash_flow',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{trans_choice('general.cash_flow',1)}}
                @if(!empty($start_date))
                    for period: <b>{{$start_date}} to {{$end_date}}</b>
                @endif
            </h3>

            <div class="box-tools pull-right">
                <button class="btn btn-sm btn-info hidden-print" onclick="window.print()">Print</button>
            </div>
        </div>
        <div class="box-body hidden-print">
            {!! Form::open(array('url' => Request::url(), 'method' => 'get','class'=>'form-horizontal', 'name' => 'form')) !!}
            <div class="row">
                <div class="col-md-4">
                    {!! Form::text('start_date',$start_date, array('class' => 'form-control date-picker', 'placeholder'=>"From Date",'required'=>'required')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::text('end_date',$end_date, array('class' => 'form-control date-picker', 'placeholder'=>"To Date",'required'=>'required')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::select('branch_id',$branches,$branch_id, array('class' => 'form-control ','placeholder'=>'Select Branch','id'=>'')) !!}
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-2">
                        <span class="input-group-btn">
                          <button type="submit" class="btn bg-olive btn-flat">{{trans_choice('general.search',1)}}!
                          </button>
                        </span>
                        <span class="input-group-btn">
                          <a href="{{Request::url()}}"
                             class="btn bg-purple  btn-flat pull-right">{{trans_choice('general.reset',1)}}!</a>
                        </span>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
        <!-- /.box-body -->

    </div>
    <!-- /.box -->
    <div class="box box-info">
        <div class="box-body table-responsive no-padding">

            <div class="col-sm-6">
                <table class="table table-bordered table-condensed table-hover">
                    <tbody>
                    <tr style="background-color: #F2F8FF">
                        <td></td>
                        <td style="text-align:right"><b>{{trans_choice('general.balance',1)}} </b></td>
                    </tr>
                    <tr>
                        <td class="text-blue"><b>{{trans_choice('general.receipt',2)}}</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <b>{{trans_choice('general.contribution',2)}}</b>
                        </td>
                        <td style="text-align:right">{{number_format($contributions,2)}}</td>
                    </tr>
                    <tr>
                        <td>
                            <b>{{trans_choice('general.pledge',2)}}</b>
                        </td>
                        <td style="text-align:right">{{number_format($pledges,2)}}</td>
                    </tr>
                    <tr>
                        <td><b>{{trans_choice('general.event',1)}} {{trans_choice('general.payment',2)}}</b></td>
                        <td style="text-align:right">{{number_format($events,2)}}</td>
                    </tr>
                    <tr>
                        <td><b>{{trans_choice('general.other_income',1)}}</b></td>
                        <td style="text-align:right">{{number_format($other_income,2)}}</td>
                    </tr>
                    <tr class="active">
                        <td style="border-bottom:1px solid #000000">
                            <b>{{trans_choice('general.total',1)}} {{trans_choice('general.receipt',2)}} (A)</b></td>
                        <td style="text-align:right; border-bottom:1px solid #000000"
                            class="text-bold">{{number_format($total_receipts,2)}}</td>
                    </tr>
                    <tr>
                        <td class="text-blue"><b>{{trans_choice('general.payment',2)}}</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>{{trans_choice('general.expense',2)}}</b></td>
                        <td style="text-align:right">{{number_format($expenses,2)}}</td>
                    </tr>
                    <tr>
                        <td><b>{{trans_choice('general.payroll',1)}}</b></td>
                        <td style="text-align:right">{{number_format($payroll,2)}}</td>
                    </tr>
                    <tr class="active">
                        <td style="border-bottom:1px solid #000000">
                            <b>{{trans_choice('general.total',1)}} {{trans_choice('general.payment',2)}} (B)</b></td>
                        <td style="text-align:right; border-bottom:1px solid #000000 " class="text-red text-bold">
                            ({{number_format($total_payments,2)}})
                        </td>
                    </tr>
                    <tr class="info">
                        <td style="color:green;">
                            <b>{{trans_choice('general.total',1)}} {{trans_choice('general.cash',1)}} {{trans_choice('general.balance',1)}}
                                (A) - (B)</b></td>
                        <td style="text-align:right"><b>{{number_format($cash_balance,2)}}</b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer-scripts')

@endsection
