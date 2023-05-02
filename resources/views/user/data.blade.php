@extends('layouts.master')
@section('title')
    {{ trans_choice('general.user',2) }}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.user',2) }}</h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('users.create'))
                    <a href="{{ url('user/create') }}" class="btn btn-info btn-xs">
                        {{ trans_choice('general.add',1) }} {{ trans_choice('general.user',1) }}
                    </a>
                @endif
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table  table-bordered table-hover table-stripped" id="data-table">
                <thead>
                <tr>
                    <th>{{ trans('general.id') }}</th>
                    <th>{{ trans('general.name') }}</th>
                    <th>{{ trans('general.gender') }}</th>
                    <th>{{ trans('general.phone') }}</th>
                    <th>{{ trans_choice('general.email',1) }}</th>
                    <th>{{ trans('general.created_at') }}</th>
                    <th>{{ trans_choice('general.action',1) }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
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
            ajax: '{!! url('user/get_users') !!}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'users.first_name'},
                {data: 'gender', name: 'gender'},
                {data: 'phone', name: 'phone', orderable: false},
                {data: 'email', name: 'email', orderable: false},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: 'frtip',
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
            responsive: false
        });
    </script>
@endsection
