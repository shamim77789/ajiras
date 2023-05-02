@extends('layouts.master')
@section('title')
    {{trans_choice('general.member',1)}} {{trans_choice('general.transfer',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.member',1)}} {{trans_choice('general.transfer',1)}}</h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('members.create'))
                    <a href="{{ url('member/transfer/create') }}"
                       class="btn btn-info btn-sm">{{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}  {{trans_choice('general.transfer',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body ">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered table-condensed table-hover">
                    <thead>
                    <tr style="background-color: #D1F9FF">
                        <th>{{trans_choice('general.id',1)}}</th>
                        <th>{{trans_choice('general.member',1)}}</th>
                        <th>{{trans_choice('general.transfer_from',1)}}</th>
						<th>{{trans_choice('general.dioces',1)}}</th>
                        <th>{{trans_choice('general.transfer_to',1)}}</th> 
                        <th>{{trans_choice('general.transfer_date',1)}}</th>
						<!--<th>{{trans_choice('general.member_number',1)}}</th>-->
						<th>{{trans_choice('general.reason',1)}}</th>
						<th>{{trans_choice('general.status',1)}}</th>
                      <th>{{trans_choice('general.action',1) }}</th>
                    </tr>
                    </thead>
					<tbody>
						@if(!empty($members_transfer))
							@foreach($members_transfer as $key => $transfer)
							<tr>
								<td> {{$key + 1}}</td>
								<td>{{$transfer->member->first_name}}</td>
								<td>{{$transfer->member_from_branch->name}}</td>
								<td>{{$transfer->dioce->name}}</td>
								<td>{{$transfer->member_to_branch->name}}</td>
								<td>{{date('F j, Y', strtotime($transfer->transfer_date))}}</td>
								<!--<td>{{$transfer->member_number}}</td>-->
								<td>{{$transfer->reason}}</td>
								<td>
									@if($transfer->member_status == '0')
									<span class="badge badge-warning">Pending</span>
									@else
									<span class="badge badge-primary">Transferred</span>
									@endif
								</td>
								<td>
									<div class="btn-group">
										<button type="button" class="btn btn-info btn-flat dropdown-toggle"
												data-toggle="dropdown" aria-expanded="false">
											{{ trans('general.choose') }} <span class="caret"></span>
											<span class="sr-only">Toggle Dropdown</span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li><a href="{{ url('member/transfer/'.$transfer->id.'/edit') }}"><i
															class="fa fa-edit"></i> {{ trans('general.edit') }} </a></li>
											<li><a href="{{ url('member/'.$transfer->member->id.'/edit/'.$transfer->id) }}"><i
															class="fa fa-hourglass-start"></i> {{ trans('general.transfer') }} </a></li>
											<li><a href="{{ url('member/transfer/'.$transfer->id.'/delete') }}"
												   class="delete"><i
															class="fa fa-trash"></i> {{ trans('general.delete') }} </a></li>
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
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/datatable/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/media/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/buttons.colVis.min.js')}}"></script>

	<script>
        $('#data-table').DataTable();
		
		$(document).on('change','.status',function(){
			var status = $(this).val();
			var member_transfer_id = $(this).attr('data-id');
			console.log(member_transfer_id);
			  $.ajax({
              url: "{{ url('member/transfer/status') }}",
              method: 'post',
              data: {
                "_token": "{{ csrf_token() }}",
                 status : status,
				 member_transfer_id : member_transfer_id
              },
              success: function(result)
			  {
				Swal.fire({
                    title: "Success",
                    text: "Status Changed Successfully!",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false,
                });  
			  }
                      
            });			
		});
    </script>
@endsection
