@extends('layouts.master')
@section('title')
    {{trans_choice('general.contribution_type',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{trans_choice('general.contribution_type',1)}}
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
                    {!! Form::select('group_type',$group_type,'', array('class' => 'form-control ','placeholder'=>'Select Group Type','id'=>'')) !!}
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
						<td style="text-align:center"><b>S#</b></td>
                        <td style="text-align:center"><b>{{trans_choice('general.group',1)}} </b></td>
                        <td style="text-align:center"><b>{{trans_choice('general.amount',1)}} </b></td>
                    </tr>
                    <tr style="background-color: #F2F8FF">
						<td style="text-align:center"></td>
                        <td style="text-align:center"><b>Total </b></td>
                        <td style="text-align:center"></td>
                    </tr>
					
					</tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('footer-scripts')

@endsection
