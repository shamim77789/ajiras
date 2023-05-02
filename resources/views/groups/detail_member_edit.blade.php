@extends('layouts.master')
@section('title')
    Edit Group Member
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 mt-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Details</h3>
                </div>
                <div class="box-body">
					<div><b>Full Name :</b> {{$get_member_data->first_name}} {{$get_member_data->middle_name}} {{$get_member_data->last_name}}</div>
					<div><b>Member Number :</b>{{$get_member_data->member_number}}</div>
					<div><b>Phone :</b>{{$get_member_data->mobile_phone}}</div>
					<div><b>Email :</b>{{$get_member_data->email}}</div>
				</div>
                </div>
                <!-- /.box-body -->

            </div>
		<form action="{{url('Groups/'.$get_member_data->id.'/update_member_group')}}" method="POST">
			{{@csrf_field()}}
			<div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
					<h3 class="box-title"><b>Group Name </b></h3>
					<select class="form-control group_name" name="group_name">
						@if(!empty($group))
							@foreach($group as $key => $value)
				<option value="{{$value->id}}" <?php echo ($value->id == $get_member_data->group_id ? 'selected' : ''); ?>>{{$value->group_name}}</option>
							@endforeach
						@endif
					</select>
                </div>
                <div class="box-body">
					<b>Group Type : </b>{!! $groups->group_type !!}
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<b>Created At : </b> {{date('F j Y', strtotime($groups->created_at))}}
                </div>
                <div class="box-footer">
					<button type="submit" class="btn btn-primary" >Update</button>
                </div>
			</div>
            <!-- /.box -->
        </div>
		</form>

            <!-- /.box -->
        </div>
    </div>
@endsection