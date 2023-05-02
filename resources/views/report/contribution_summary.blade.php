@extends('layouts.master')
@section('title')
    {{trans_choice('general.contribution_summary',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{trans_choice('general.contribution_summary',1)}}
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
                <div class="col-md-3">
                    {!! Form::text('start_date',$start_date, array('class' => 'form-control date-picker', 'placeholder'=>"From Date",'required'=>'required')) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::text('end_date',$end_date, array('class' => 'form-control date-picker', 'placeholder'=>"To Date",'required'=>'required')) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::select('fund_type',$funds,'', array('class' => 'form-control ','placeholder'=>'Select Fund Type','id'=>'')) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::select('batches',$batches,'', array('class' => 'form-control ','placeholder'=>'Select Batches','id'=>'')) !!}
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
						<td style="text-align:center"><b>{{trans_choice('general.sno',1)}} </b></td>
                        <td style="text-align:center"><b>{{trans_choice('general.contribution_fund',1)}} </b></td>
                        <td style="text-align:center"><b>{{trans_choice('general.contribution',1)}} </b></td>
                        <td style="text-align:center"><b>{{trans_choice('general.expense',1)}} </b></td>
                        <td style="text-align:center"><b>{{trans_choice('general.difference',1)}} </b></td>
                    </tr>
					
					</tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer-scripts')

@endsection
