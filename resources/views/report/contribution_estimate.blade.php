@extends('layouts.master')
@section('title')
{{trans_choice('general.contribution_estimate',1)}}
@endsection
@section('content')
<div class="box box-primary">
   <div class="box-header with-border">
      <h3 class="box-title">
         {{trans_choice('general.contribution_estimate',1)}}
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
            <select class="quarter form-control" name="quarter[]">
               <option value="0">Select Quarter</option>
               <option value="1">January - March</option>
               <option value="2">April - June</option>
               <option value="3">July - September</option>
               <option value="4">October - December</option>
            </select>
         </div>
         <div class="col-md-3">
            {!! Form::select('fund_type',$funds,'', array('class' => 'form-control ','placeholder'=>'Select Fund Type','id'=>'')) !!}
         </div>
         <div class="col-md-3 mt-3">
			 <select class="dioces_id form-control" name="dioces_id" style="margin-top:10px;">
				 <option value="0">Select Dioces</option>
				 @if(!empty($dioces))
					@foreach($dioces as $key => $dioce)
						<option value="{{$dioce->id}}">{{$dioce->name}}</option>
				 	@endforeach
				 @endif
			 </select>
		  </div>
         <div class="col-md-3">
			 <select class="state_id form-control" name="state_id" style="margin-top:10px;">
				 <option value="0">Select State</option>
			<!--	@if(!empty($state))
					@foreach($state as $key => $value)
				 		<option value="{{$value->id}}">{{$value->name}}</option>
				    @endforeach
				@endif -->
			 </select>		  
		  </div>
         <div class="col-md-3 mt-3">
			 <select class="branch_id form-control" name="branch_id" style="margin-top:10px;">
				 <option value="0">Select Branch</option>
			 </select>
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
                  <td style="text-align:center"><b>{{trans_choice('general.contribution_type',1)}} </b></td>
                  <td style="text-align:center"><b>{{trans_choice('general.estimate',1)}} </b></td>
                  <td style="text-align:center"><b>{{trans_choice('general.actual',1)}} </b></td>
                  <td style="text-align:center"><b>{{trans_choice('general.difference',1)}} </b></td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection
@section('footer-scripts')
<script>
	$(document).on('change','.dioces_id',function(){
		var dioces_id = $(this).val();
		$.ajax({
              url: "{{ url('general/states') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 dioces_id: dioces_id
              },
              success: function(result){
				 var states = result;
				 var Html = '<option value="0">Select State</option>';
				  for(i = 0; i < states.length; i++)
				  {
					  Html += '<option value='+states[i]['id']+'>'+states[i]['name']+'</option>';
				  }
				  $('.state_id').empty();
				  $('.state_id').append(Html);
			  }              
		});
	});
	$(document).on('change','.state_id',function(){
		var state_id = $(this).val();
		$.ajax({
              url: "{{ url('general/branch') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 state_id: state_id
              },
              success: function(result){
				 var branches = result;
				 var Html = '<option value="0">Select Branch</option>';
				 if(branches.length > 0)
 					 Html += '<option value="-1">All Branches</option>';
				 for(i = 0; i < branches.length; i++)
				 {
				   Html += '<option value='+branches[i]['id']+'>'+branches[i]['name']+'</option>';
				 }
				 $('.branch_id').empty();
				 $('.branch_id').append(Html);
			  }              
		});
	});
</script>
@endsection