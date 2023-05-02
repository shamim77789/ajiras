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

                    <div class="box-tools pull-right">
                        @if(Sentinel::hasAccess('branches.assign'))
                            <a href="#" data-toggle="modal" data-target="#addUser"
                               class="btn btn-info btn-sm">{{trans_choice('general.add',1)}} {{trans_choice('general.user',1)}}</a>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered table-condensed table-hover">
                            <thead>
                            <tr style="background-color: #D1F9FF">
                                <th>Full Name</th>
                                <th>Member Number</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
								@if(!empty($group_members))
									@foreach($group_members as $members)
									<tr>
										<td>{{$members->first_name}} {{$members->middle_name}} {{$members->last_name}}</td>
										<td>{{$members->member_number}}</td>
										<td>{{$members->mobile_phone}}</td>
										<td>{{$members->email}}</td>
										<td>
											<div class="btn-group">
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    {{ trans('general.choose') }} <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    @if(Sentinel::hasAccess('Groups.view'))
                                                        <li><a href="{{ url('Groups/'.$members->id.'/detail_member_view') }}"><i
                                                                        class="fa fa-search"></i> Details
                                                            </a></li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('Groups.view'))
                                                        <li><a href="{{ url('Groups/'.$members->id.'/edit_member') }}"><i
                                                                        class="fa fa-edit"></i> Edit
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('Groups.view'))
                                                        <li>
                                                            <a href="{{ url('Groups/'.$members->id.'/remove_member') }}"
                                                               class="delete"><i
                                                                        class="fa fa-trash"></i> Delete
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
										</td>
									</tr>
									@endforeach
								@endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->

            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection