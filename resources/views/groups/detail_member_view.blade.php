@extends('layouts.master')
@section('title')
    {{ $groups->group_name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
					<h3 class="box-title"><b>Group Name :</b>{{ $groups->group_name }}</h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                <div class="box-body">
					<b>Group Type : </b>{!! $groups->group_type !!}
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
					<b>Created At : </b> {{date('F j Y', strtotime($groups->created_at))}}
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Details</h3>

                </div>
                <div class="box-body">
					<div>
						<b>Full Name : </b> {{$get_member_data->first_name}} {{$get_member_data->middle_name}} {{$get_member_data->last_name}} 
					</div>
					<div>
						<b>Member Number : </b> {{$get_member_data->member_number}} 
					</div>					
					<div>
						<b>Phone Number : </b> {{$get_member_data->mobile_phone}} 
					</div>					
					<div>
						<b>Email : </b> {{$get_member_data->email}} 
					</div>	
                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection