@extends('layouts.master')
@section('title')
    Recorded Deductions
@endsection
@section('content')
    <div class="box box-primary" id="app">
        <div class="box-header with-border">
            <h3 class="box-title">Deductions </h3>

            <div class="box-tools pull-right">
                <!-- @if(Sentinel::hasAccess('contributions.create')) -->
                    <a href="{{ route('deduction.create') }}"
                       class="btn btn-info btn-sm">Add Deduction</a>
                <!-- @endif -->
            </div>
        </div>
        <div class="box-body">

            <div class="form-group">
                <label class="control-label">Select Branch</label>
                <select name="branches" class="branches form-control select2">
                    <option value="">select branch---</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="table-responsive">
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
    <script type="text/javascript">
        $(document).on('change', '.branches', function(){

            let branch_id = $(this).val();

            $.ajax({
                url: "{{ route('branch_deductions') }}",
                method: 'GET',
                dataType: 'html',
                data: {id: branch_id},
                success: function(res) {
                    $('.table-responsive').html(res);
                }
            });

        });
    </script>
    <script>
        // var url = "{!! url('contribution/get_contributions?') !!}";
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            // ajax: url,
            // columns: [
            //     {data: 'id', name: 'id'},
            //     {data: 'branch', name: 'branches.name'},
            //     {data: 'batch', name: 'contribution_batches.name'},
            //     {data: 'contribution_type', name: 'contribution_types.name'},
            //     {data: 'member', name: 'members.first_name'},
            //     {data: 'amount', name: 'amount', searchable: false},
            //     {data: 'payment_method', name: 'payment_methods.name'},
            //     {data: 'date', name: 'date'},
            //     {data: 'notes', name: 'notes', orderable: false},
            //     {data: 'action', name: 'action', orderable: false, searchable: false}
            // ],
            dom: 'Bfrtip',
            buttons: [
                {extend: 'copy', 'text': '{{ trans('general.copy') }}'},
                {extend: 'excel', 'text': '{{ trans('general.excel') }}'},
                {extend: 'pdf', 'text': '{{ trans('general.pdf') }}'},
                {extend: 'print', 'text': '{{ trans('general.print') }}'},
                {extend: 'csv', 'text': '{{ trans('general.csv') }}'},
                {extend: 'colvis', 'text': '{{ trans('general.colvis') }}'}
            ],
            "paging": true,
            "lengthChange": true,
            "displayLength": 15,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[5, "desc"]],
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
