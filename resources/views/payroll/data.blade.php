@extends('layouts.master')
@section('title'){{trans_choice('general.payroll',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.payroll',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table id="view-repayments"
                       class="table table-bordered table-condensed table-hover no-footer">
                    <thead>
                    <tr  role="row">
                        <th>
                            {{trans_choice('general.staff',1)}}
                        </th>
                        <th> {{trans_choice('general.date',1)}}</th>
                        <th>
                            {{trans_choice('general.gross',1)}} {{trans_choice('general.amount',1)}}
                        </th>
                        <th>
                            {{trans_choice('general.deduction',2)}}
                        </th>
                        <th>
                            {{trans_choice('general.net',1)}} {{trans_choice('general.amount',1)}}
                        </th>
                        <th>
                            {{trans_choice('general.paid',1)}} {{trans_choice('general.amount',1)}}
                        </th>
                        <th>{{trans_choice('general.recurring',1)}}</th>
                        <th>
                            {{trans_choice('general.action',1)}}
                        </th>
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
    <script>
        $(document).ready(function () {
            $('.deletePayment').on('click', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                swal({
                    title: 'Are you sure?',
                    text: 'If you delete a payment, a fully paid loan may change status to open.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
                    cancelButtonText: 'Cancel'
                }).then(function () {
                    window.location = href;
                })
            });
        });
    </script>
    <script src="{{ asset('assets/plugins/datatable/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/media/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/datatable/extensions/Buttons/js/buttons.colVis.min.js')}}"></script>
    <script>

        $('#view-repayments').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('payroll/get_payroll?') !!}',
            columns: [
                {data: 'user', name: 'users.first_name'},
                {data: 'date', name: 'date'},
                {data: 'gross_amount', name: 'gross_amount', orderable: false, searchable: false},
                {data: 'total_deductions', name: 'total_deductions', orderable: false, searchable: false},
                {data: 'net_amount', name: 'net_amount', orderable: false, searchable: false},
                {data: 'paid_amount', name: 'paid_amount'},
                {data: 'recurring', name: 'recurring'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: 'Bfrtip',
            buttons: [
                {extend: 'copy', 'text': '{{ trans('general.copy') }}' , exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6 ]
                },},
                {extend: 'excel', 'text': '{{ trans('general.excel') }}' , exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6 ]
                },},
                {extend: 'pdf', 'text': '{{ trans('general.pdf') }}' , exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6 ]
                },},
                {extend: 'print', 'text': '{{ trans('general.print') }}' , exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6 ]
                },},
                {extend: 'csv', 'text': '{{ trans('general.csv') }}' , exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6 ]
                }, },
                {extend: 'colvis', 'text': '{{ trans('general.colvis') }}' , exportOptions: {
                    columns: [ 0, 1, 2, 3,4,5,6 ]
                },}
            ],
            "paging": true,
            "lengthChange": true,
            "displayLength": 15,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "columnDefs": [
                {"orderable": false, "targets": [5, 6]}
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
