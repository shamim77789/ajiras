@extends('layouts.master')
@section('title')
    {{trans_choice('general.follow_up',2)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.follow_up',2)}} </h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('follow_ups.create'))
                    <a href="{{ url('follow_up/create') }}"
                       class="btn btn-info btn-sm">{{trans_choice('general.add',1)}} {{trans_choice('general.follow_up',1)}} </a>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>{{trans_choice('general.id',1)}}</th>
                        <th>{{trans_choice('general.branch',1)}}</th>
                        <th>{{trans_choice('general.assigned_to',1)}}</th>
                        <th>{{trans_choice('general.member',1)}}</th>
                        <th>{{trans_choice('general.category',1)}}</th>
                        <th>{{trans_choice('general.status',1)}}</th>
                        <th>{{trans_choice('general.note',2)}}</th>
                        <th>{{trans_choice('general.created_at',2)}}</th>
                        <th>{{ trans_choice('general.action',1) }}</th>
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
            ajax: '{!! url('follow_up/get_follow_ups?assigned_to_id='.Request::get('assigned_to_id')) !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'branch', name: 'branches.name'},
                {data: 'assigned_to', name: 'users.first_name'},
                {data: 'member', name: 'members.first_name'},
                {data: 'follow_up_category', name: 'follow_up_categories.name'},
                {data: 'status', name: 'status', searchable: false},
                {data: 'notes', name: 'notes', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at',  searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: 'Bfrtip',
            buttons: [
                {extend: 'copy', 'text': '{{ trans('general.copy') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7]}},
                {extend: 'excel', 'text': '{{ trans('general.excel') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7]}},
                {extend: 'pdf', 'text': '{{ trans('general.pdf') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7]}},
                {extend: 'print', 'text': '{{ trans('general.print') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7]}},
                {extend: 'csv', 'text': '{{ trans('general.csv') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7]}},
                {extend: 'colvis', 'text': '{{ trans('general.colvis') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7]}}
            ],
            "paging": true,
            "lengthChange": true,
            "displayLength": 15,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "order": [[6, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": [7]}
            ],
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
            responsive: false
        });
    </script>
@endsection
