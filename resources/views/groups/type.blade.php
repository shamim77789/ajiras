@extends('layouts.master')
@section('title')
	Group Type
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Group Type</h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('Groups.type'))
                    <a href="{{url('/Groups/add_type')}}" class="btn btn-info btn-sm">Add {{trans_choice('groups',1)}} Type</a>
                @endif
            </div>
        </div>
        <div class="box-body ">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered table-condensed table-hover">
                    <thead>
                    <tr style="background-color: #D1F9FF">
                        <th>Name</th>
						<th>Branch</th>
						<th>Action</th>
                    </tr>
                    </thead>

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
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('Groups/get_group_types?') !!}',
            columns: [
                { data: 'group_type', name: 'group_type' },
                { data: 'branch_name', name: 'branch_name' },
 			    { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: 'Bfrtip',
            buttons: [
                {extend: 'copy', 'text': '{{ trans('general.copy') }}', exportOptions: {columns: [ 0, 1 ]}},
                {extend: 'excel', 'text': '{{ trans('general.excel') }}', exportOptions: {columns: [ 0, 1 ]}},
                {extend: 'pdf', 'text': '{{ trans('general.pdf') }}', exportOptions: {columns: [ 0, 1 ]}},
                {extend: 'print', 'text': '{{ trans('general.print') }}', exportOptions: {columns: [ 0, 1 ]}},
                {extend: 'csv', 'text': '{{ trans('general.csv') }}', exportOptions: {columns: [ 0, 1 ]}},
                {extend: 'colvis', 'text': '{{ trans('general.colvis') }}', exportOptions: {columns: [ 0, 1 ]}}
            ],
            "paging": true,
            "lengthChange": true,
            "displayLength": 15,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "language": {
                "lengthMenu": "{{ trans('general.lengthMenu') }}",
                "zeroRecords": "{{ trans('general.zeroRecords') }}",
                "info": "{{ trans('general.info') }}",
                "infoEmpty": "{{ trans('general.infoEmpty') }}",
                "search": "{{ trans('general.search') }}",
                "infoFiltered": "{{ trans('general.infoFiltered') }}",
                "paginate": {
                    "first": "{{ trans('general.first') }}",
                    "last": "{{ trans('general.last') }}",
                    "next": "{{ trans('general.next') }}",
                    "previous": "{{ trans('general.previous') }}"
                }
            },
        });
		
		
		$(document).on('click','.create_group_type',function(){
			var successFlag = true;
			var array2 = ['group_type'];
			  for (var i = 0, l = array2.length; i < l; i++) {
                    var Id = array2[i];
                    $('.' + Id).each(function(i) {
                        if ($(this).val() == '' || $(this).val() == 0) {
                            successFlag = false;
                            $(this).focus();
                            $(this).css('border-color', 'red');

                        } else {
                            $(this).css('border-color', '');
                        }
                    });
                }
			
			if(successFlag == true)
			{
				var group_type = $('.group_type').val();
				  $.ajax({
						  url: "{{ url('/groups/create_type') }}",
						  method: 'post',
						  data: {
							"_token": "{{ csrf_token() }}",
							 group_type : group_type
						  },
						  success: function(result)
						  {
							  location.reload(true);

						  }

						});
			}
		
		});
		
		$(document).on('click','.edit_group_type',function(){
			$('.modal-title').html('Edit Group Type');
			var group_type = $(this).attr('data-type');
			var group_id = $(this).attr('data-id');
			
			$('.group_type_id').val(group_id);
			$('.group_type').val(group_type);
			$('.create_group_type').addClass('update_group_type');
			$('.update_group_type').removeClass('create_group_type');
			$('.update_group_type').html('Update');
			
		});
		
		$(document).on('click','.update_group_type',function(){
			var id = $('.group_type_id').val();
			var group_type = $('.group_type').val();
			var successFlag = true;
			var array2 = ['group_type'];
			for (var i = 0, l = array2.length; i < l; i++) {
				var Id = array2[i];
				$('.' + Id).each(function(i) {
					if ($(this).val() == '' || $(this).val() == 0) {
						successFlag = false;
						$(this).focus();
						$(this).css('border-color', 'red');

					} else {
						$(this).css('border-color', '');
					}
				});
			}
			
			if(successFlag == true)
			{
				$.ajax({
				  url: "{{ url('groups/"+id+"/edit_group_type') }}",
				  method: 'post',
				  data: {
					"_token": "{{ csrf_token() }}",
					id : id,
					group_type: group_type
				  },
				  success: function(result)
				  {
					  location.reload(true);
				  }

				});
			}
			
		});
		
    </script>
@endsection
