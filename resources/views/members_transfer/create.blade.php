@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}} {{trans_choice('general.transfer',1)}} {{trans_choice('general.request',1)}}
@endsection
@section('content')
<style>
	.ins_class
	{
		position: absolute; 
		top: -20%; 
		left: -20%; 
		display: block; 
		width: 140%; 
		height: 140%; 
		margin: 0px; 
		padding: 0px; 
		background: rgb(255, 255, 255); 
		border: 0px; 
		opacity: 0;
	}
	.checkbox_class{
		position: absolute; 
		top: -20%; 
		left: -20%; 
		display: block; 
		width: 140%; 
		height: 140%; 
		margin: 0px; 
		padding: 0px; 
		background: rgb(255, 255, 255); 
		border: 0px; 
		opacity: 0;
	}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}} {{trans_choice('general.transfer',1)}} {{trans_choice('general.request',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
{!! Form::open(array('url' => url('member/transfer/store'), 'method' => 'post', 'id' => 'add_member_form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
				<select class="form-control select2 member_id" name="member_id" placeholder="Select Member">
					<option value="0">Select Member</option>
					@if(!empty($members))
						@foreach($members as $member)
							<option value="{{$member->id}}" data-branch_id="{{$member->branch_id}}">
								{{$member->first_name}} {{$member->middle_name}} {{$member->last_name}}
							</option>
						@endforeach
					@endif
				</select>
            </div>
<!--
			<div class="form-group">
                {!! Form::label('member_id',trans_choice('general.member_number',1),array('class'=>' control-label')) !!}
				<input name="member_number" class="form-control member_number" placeholder="Member Number">
			</div>	
-->			
            <div class="form-group">
                {!! Form::label('branch_id','Transfer From',array('class'=>' control-label')) !!}
				<select class="form-control select2 transfer_from_branch" name="transfer_from_branch" placeholder="Select Branch">
					<option value="0">Select Branch</option>
					@if(!empty($branches))
						@foreach($branches as $branch)
							<option value="{{$branch->id}}">{{$branch->name}}</option>
						@endforeach
					@endif
				</select>
            </div>	
            <div class="form-group">
                {!! Form::label('dioces_id','Dioces',array('class'=>' control-label')) !!}
				<select class="form-control select2 dioces" name="dioces" placeholder="Select Dioces">
					<option value="0">Select Dioces</option>
					@if(!empty($dioces))
						@foreach($dioces as $dioce)
							<option value="{{$dioce->id}}">{{$dioce->name}}</option>
						@endforeach
					@endif
				</select>
            </div>			
			

			
			<div class="form-group">
                {!! Form::label('branch_id','Transfer To',array('class'=>' control-label')) !!}
				<select class="form-control select2 transfer_to_branch" name="transfer_to_branch" placeholder="Select Branch">
					<option value="0">Select Branch</option>
				</select>
            </div>			
			
            <div class="form-group">
                {!! Form::label('transfer_date',trans_choice('general.transfer_date',1),array('class'=>'')) !!}
				<input type="text" name="transfer_date" class="form-control date-picker" placeholder="yyyy-mm-dd">
            </div>			
			
            <div class="form-group">
                {!! Form::label('transfer_date',trans_choice('general.reason',1),array('class'=>'')) !!}
				<textarea class="form-control reason" name="reason"></textarea>
			</div>
			

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right" id="add_member">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
<!-- /.box -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
@endsection
@section('footer-scripts')
    <script>
	$(document).on('change','.member_id',function(){
		var member_id = $(this).val();
 		var branch_id = $(this).find('option:selected').attr('data-branch_id');

		$('.transfer_from_branch').val(branch_id);
		var title = $(".transfer_from_branch option:selected" ).text();
		
		$('.transfer_from_branch').parent().find('.select2-selection__rendered').attr('title',title);
		$('.transfer_from_branch').parent().find('.select2-selection__rendered').html(title);

	});
	
	/*Dioces*/
	$(document).on('change','.dioces',function(){
		var dioces = $(this).val();
		$.ajax({
			url: "{{ url('/member/transfer/get-branches') }}",
			method: 'post',
			data: 
			{
				"_token": "{{ csrf_token() }}",
				dioces : dioces
			},
			success: function(result)
			{
				var Html = '<option value="0">Select Branch</option>';
				for(var i = 0; i < result.length; i++)
				{
					Html += '<option value="'+result[i]['id']+'">'+result[i]['name']+'</option>';
				}
				$('.transfer_to_branch').empty();
				$('.transfer_to_branch').append(Html);
			}
		});
	});
    </script>
@endsection

