@extends('layouts.master')
@section('title')
    {{ trans_choice('general.announcement',1) }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans_choice('general.announcement',1) }}</h3>
                    <div class="box-tools pull-right">

                    </div>
                </div>
                <div class="box-body">
					<div class="container-fluid">
						<div class="row">
							<div class="col-lg-12 col-sm-12 col-md-12">
								<span><label>Name : </label></span>
								<span> {{$announcement->name}}</span>
							</div>
							<div class="col-lg-12 col-sm-12 col-md-12">
								<span><label>Current Date : </label></span>
								<span> {{date('F j, Y', strtotime($announcement->current_date))}}</span>
							</div>
							<div class="col-lg-12 col-sm-12 col-md-12 mt-1">
								<span><label>Announcement Type : </label></span>
								<span> {{$announcement->announcement_types->name}}</span>
							</div>
							<div class="col-lg-12 col-sm-12 col-md-12 mt-1">
								<span><label>Announcement Date : </label></span>
								<span> {{date('F j, Y', strtotime($announcement->announcement_date))}}</span>
							</div>
							<div class="col-lg-12 col-sm-12 col-md-12 mt-1">
								<span><label>Notes : </label></span>
								<span> {{$announcement->notes}}</span>
							</div>

						</div>
					</div>
				</div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection