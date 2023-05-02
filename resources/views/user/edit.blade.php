@extends('layouts.master')
@section('title')
    {{ trans_choice('general.edit',1) }} {{ trans_choice('general.user',1) }}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.edit',1) }} {{ trans_choice('general.user',1) }}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => 'user/'.$user->id.'/update','class'=>'form-horizontal',"enctype" => "multipart/form-data")) !!}
        <div class="box-body">
            <div class="col-md-12">
                {!! Form::hidden('previous_role',$selected,array('class'=>'form-control','required'=>'required')) !!}
                <div class="form-group">
                    {!!  Form::label(trans('general.first_name'),null,array('class'=>'control-label')) !!}

                    {!! Form::text('first_name',$user->first_name,array('class'=>'form-control','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans('general.last_name'),null,array('class'=>'control-label')) !!}
                    {!! Form::text('last_name',$user->last_name,array('class'=>'form-control','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans('general.gender'),null,array('class'=>' control-label')) !!}
                    {!! Form::select('gender', array('Male' =>trans('general.male'), 'Female' => trans('general.female')),$user->gender,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans('general.phone'),null,array('class'=>'control-label')) !!}
                    {!! Form::text('phone',$user->phone,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group ">
                    {!!  Form::label(trans_choice('general.email',1),null,array('class'=>'control-label')) !!}
                    {!! Form::email('email',$user->email,array('class'=>'form-control','required'=>'required')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans('general.password'),null,array('class'=>'control-label')) !!}
                    {!! Form::password('password',array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans('general.repeat_password'),null,array('class'=>'control-label')) !!}
                    {!! Form::password('rpassword',array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans_choice('general.role',1),null,array('class'=>' control-label')) !!}
                    {!! Form::select('role',$role,$selected,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans_choice('general.dioces',2),null,array('class'=>'control-label')) !!}
					<select class="form-control dioces" name="dioces">
						<option value="0">Select Dioces</option>
						<option value="-1">All Dioces</option>
						@if(!empty($dioces))
							@foreach($dioces as $dioce)
							<option value="{{$dioce->id}}" {{$dioce->id == $user->dioces ? 'selected' : ''}}>{{$dioce->name}}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="form-group state_province_append">
				<?php echo $state;?></div>
				<div class="form-group branches_append"><?php echo $branch;?></div>
				<div class="form-group">
                    {!!  Form::label(trans('general.address'),null,array('class'=>'control-label')) !!}
                    {!! Form::textarea('address',$user->address,array('class'=>'form-control')) !!}
                </div>
                <div class="form-group">
                    {!!  Form::label(trans_choice('general.note',2),null,array('class'=>'control-label')) !!}

                    {!! Form::textarea('notes',$user->notes,array('class'=>'form-control')) !!}
                </div>

            </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
	<script>

		
		$(document).on('change','.dioces',function(){
			var dioces = $(this).val();
			$.ajax({
              url: "{{ url('user/getstate') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 dioces : dioces
              },
              success: function(result)
			  {
				var counter = result.length;
				$('.state_province_append').empty();				  
				$('.branches_append').empty();
				var Html = '<label>State / Province</label>';
				if(counter > 0)
				{
					Html += '<div class="container">';
					Html += '<div class="row">';
					for(var i = 0; i < counter; i++)
					{
						Html += '<div class="col-lg-3">';
						Html += '<input type="checkbox" name="state_province[]" class="state_province" value="'+result[i]['id']+'">';
						Html += '<span style="margin-left:5px;">'+result[i]['name']+'</span>';
						Html += '</div>';
					}
					Html += '<div class="col-lg-12 mt-5" style="margin-top:10px;">';
					Html += '<button class="btn btn-primary mt-2 get_branches" type="button">Get Branches</button>';
					Html += '</div>';
					Html += '</div>';
					Html += '</div>';
					$('.state_province_append').append(Html);				  
				}

			  }                      
            });
			

		});
		/*State Province Change*/
		$(document).on('click','.get_branches',function(){
			var dioces = [];
            $.each($("input[name='state_province[]']:checked"), function(){
                dioces.push($(this).val());
            });
			var counter = dioces.length;
			if(counter > 0)
			{
				 $.ajax({
				  url: "{{ url('user/branches') }}",
				  method: 'post',
				  data: {
					"_token": "{{ csrf_token() }}",
					 dioces: dioces
				  },
				  success: function(result)
				  {
					  $('.branches_append').empty();
					  var counter = result.length;
					  var Html = '<label>Branches</label>';
					  Html += '<div class="container">';
					  Html += '<div class="row">';
					  for(var i = 0; i < counter; i++)
					  {
						  Html += '<div class="col-lg-3">';
						  Html += '<input type="checkbox" name="branches[]" class="branches" value="'+result[i]['id']+'">';
						  Html += '<span style="margin-left:5px;">'+result[i]['name']+'</span>';
						  Html += '</div>';
					  }
					  Html += '</div>';
					  Html += '</div>';
					  $('.branches_append').append(Html);
				  }

				});
			}
			else
			{
				alert('Please Select State');
			}
		});
	</script>
@endsection