@extends('layouts.master')
@section('title')
    {{trans_choice('general.other_income',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.other_income',1)}} </h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('other_income.create'))
                    <a href="{{ url('other_income/create') }}"
                       class="btn btn-info btn-sm">{{trans_choice('general.add',1)}} {{trans_choice('general.other_income',1)}} </a>
                @endif
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>{{trans_choice('general.branch',1)}}</th>
                        <th>{{trans_choice('general.income',1)}} {{trans_choice('general.type',1)}}</th>
                        <th>{{trans_choice('general.amount',1)}}</th>
                        <th>{{trans_choice('general.date',1)}}</th>
                        <th>{{trans_choice('general.description',1)}}</th>
                        <th>{{trans_choice('general.file',2)}}</th>
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
            ajax: '{!! url('other_income/get_other_income?') !!}',
            columns: [
                {data: 'branch', name: 'branches.name'},
                {data: 'other_income_type', name: 'other_income_types.name'},
                {data: 'amount', name: 'amount'},
                {data: 'date', name: 'date'},
                {data: 'notes', name: 'notes', orderable: false, searchable: false},
                {data: 'files', name: 'files', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: 'Bfrtip',
            buttons: [
                {extend: 'copy', 'text': '{{ trans('general.copy') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5]}},
                {extend: 'excel', 'text': '{{ trans('general.excel') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5]}},
                {extend: 'pdf', 'text': '{{ trans('general.pdf') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5]}},
                {extend: 'print', 'text': '{{ trans('general.print') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5]}},
                {extend: 'csv', 'text': '{{ trans('general.csv') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5]}},
                {extend: 'colvis', 'text': '{{ trans('general.colvis') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5]}}
            ],
            "paging": true,
            "lengthChange": true,
            "displayLength": 15,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[2, "desc"]],
            "columnDefs": [
                {"orderable": false, "targets": [5]}
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
