@extends('layouts.master')
@section('title')
    {{trans_choice('general.member',2)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.member',2)}}</h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('members.create'))
                    <a href="{{ url('member/create') }}"
                       class="btn btn-info btn-sm">{{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}</a>
                @endif
            </div>
        </div>
        <div class="box-body ">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered table-condensed table-hover">
                    <thead>
                    <tr style="background-color: #D1F9FF">
                        <th>{{trans_choice('general.id',1)}}</th>
                        <th>{{trans_choice('general.branch',1)}}</th>
                        <th>{{trans_choice('general.name',1)}}</th>
                        <th>{{trans_choice('general.photo',1)}}</th>
                        <th>{{trans_choice('general.phone',1)}}</th>
                        <th>{{trans_choice('general.gender',1)}}</th>
                        <th>{{trans_choice('general.age',1)}}</th>
                        <th>{{trans_choice('general.address',1)}}</th>
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
            ajax: '{!! url('member/get_members?') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'branch', name: 'branches.name' },
                { data: 'name', name: 'members.first_name' },
                { data: 'photo', name: 'photo', orderable: false, searchable: false },
                { data: 'mobile_phone', name: 'mobile_phone' },
                { data: 'gender', name: 'gender' },
                { data: 'age', name: 'age', searchable: false },
                { data: 'address', name: 'address', orderable: false  },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            dom: 'Bfrtip',
            buttons: [
                {extend: 'copy', 'text': '{{ trans('general.copy') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7 ]}},
                {extend: 'excel', 'text': '{{ trans('general.excel') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7 ]}},
                {extend: 'pdf', 'text': '{{ trans('general.pdf') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7 ]}},
                {extend: 'print', 'text': '{{ trans('general.print') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7 ]}},
                {extend: 'csv', 'text': '{{ trans('general.csv') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7 ]}},
                {extend: 'colvis', 'text': '{{ trans('general.colvis') }}', exportOptions: {columns: [ 0, 1, 2,3,4,5,6,7 ]}}
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
    </script>
@endsection
