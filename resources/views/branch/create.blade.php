@extends('layouts.master')
@section('title')
    {{ trans_choice('general.add',1) }} {{ trans_choice('general.branch',1) }}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.add',1) }} {{ trans_choice('general.branch',1) }}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('branch/store'), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body">
            <div class="form-group">
                {!! Form::label('dioces',trans_choice('general.dioces',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select class="dioces_id form-control select2" name="dioces_id">
						@if(!empty($dioces))
							<option value="0">Select Dioces</option>
							@foreach($dioces as $value)
								<option value="{{$value->id}}">{{$value->name}}</option>
							@endforeach
						@endif
					</select>
				</div>
            </div>
            <div class="form-group">
                {!! Form::label('state',trans_choice('general.state',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
					<select class="state_id form-control select2" name="state_id">
						<option value="0">Select State</option>
					</select>
				</div>
            </div>
			<div class="form-group">
                {!! Form::label('name',trans_choice('general.name',1),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::text('name',null, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    {!! Form::textarea('notes',null, array('class' => 'form-control', 'placeholder'=>"")) !!}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{ trans_choice('general.save',1) }}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->

<script>
/*Get State Using Dioces*/
$(document).on('change','.dioces_id',function(){
	var dioces_id = $(this).val();
	if(dioces_id != '0')
	{
		    $.ajax({
              url: "{{ url('branch/get_state') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 dioces_id: dioces_id
              },
              success: function(result)
			  {
				var Html = '';
				var counter = result.length;
				if(counter > 0 )
				{
					for(var i = 0; i < counter; i++)
					{
						Html += '<option value='+result[i]['id']+'>'+result[i]['name']+'</option>';
					}
					
					$('.state_id').empty();
					$('.state_id').append(Html);
				}
			  
			  }
                      
            });
	}
});
</script>
@endsection


