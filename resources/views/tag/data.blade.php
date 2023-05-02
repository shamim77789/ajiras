@extends('layouts.master')
@section('title')
    {{trans_choice('general.tag',2)}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">

        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.tag',2)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        <div class="box-body">
            <div id="jstree_div">
                <ul>

                    <li data-jstree='{ "opened" : true }'
                        id="0">{{trans_choice('general.all',2)}} {{trans_choice('general.tag',2)}}
                        ({{\App\Models\MemberTag::count()}} {{trans_choice('general.people',2)}})
                        {!! \App\Helpers\GeneralHelper::createTreeView(0,$menus) !!}
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="editTag">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.create',2)}} {{trans_choice('general.tag',1)}}</h4>
                </div>
                {!! Form::open(array('url' => '','method'=>'post','id'=>'edit_tag_form')) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-line">
                            {!!  Form::label( 'Name',null,array('class'=>' control-label')) !!}
                            {!! Form::text('name','',array('class'=>'form-control ','required'=>'required','id'=>'name')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!!  Form::label( 'Notes',null,array('class'=>' control-label')) !!}
                        {!! Form::textarea('notes','',array('class'=>'form-control','id'=>'notes')) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">{{trans_choice('general.save',2)}}</button>
                    <button type="button" class="btn default"
                            data-dismiss="modal">{{trans_choice('general.close',2)}}</button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
            "order": [[2, "desc"]],
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
        $(document).ready(function () {


            $('#jstree_div').jstree({
                "core": {
                    "themes": {
                        "responsive": true
                    },
                    // so that create works
                    "check_callback": true,
                },
                "plugins": ["contextmenu","types", 'wholerow'],
                "contextmenu": {
                    "items": function ($node) {
                        var tree = $("#jstree_div").jstree(true);
                        return {
                            "Details": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "{{trans_choice('general.detail',2)}}",
                                "action": function (e, data) {
                                    if ($node.id == 1) {
                                        alert("Please select a child node")
                                    } else {
                                        window.location = "{{url('tag/')}}/" + $node.id + "/show";
                                    }

                                }
                            },
                            "Create": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "{{trans_choice('general.create',2)}}",
                                "action": function (e, data) {
                                    var parent = $node.id;
                                    $node = tree.create_node($node);
                                    tree.edit($node);
                                    swal({
                                        title: '{{trans_choice('general.create',2)}} {{trans_choice('general.tag',1)}}',
                                        html: '<input type="text" id="swal-name" class="swal2-input" placeholder="{{trans_choice('general.name',1)}}">' +
                                        '<textarea id="swal-notes" class="form-control" placeholder="{{trans_choice('general.note',2)}}"></textarea>',
                                        showCancelButton: true,
                                        focusConfirm: false,
                                        confirmButtonText: 'Submit',
                                        preConfirm: function () {
                                            return new Promise(function (resolve, reject) {
                                                if ($('#swal-name').val() == "") {
                                                    reject('{{trans_choice('general.please_fill_required_field',1)}}')
                                                } else {
                                                    resolve([
                                                        $('#swal-name').val(),
                                                        $('#swal-notes').val()
                                                    ])
                                                }
                                            })
                                        },
                                        allowOutsideClick: false
                                    }).then(function (name) {
                                        $.ajax({
                                            type: 'POST',
                                            data: {
                                                parent_id: parent,
                                                result: name,
                                                _token: "{{csrf_token()}}",
                                                user_id: "{{Sentinel::getUser()->id}}"
                                            },
                                            url: "{!!  url('tag/store') !!}",
                                            dataType: "json",
                                            success: function (d) {
                                                tree.set_id($node, d.id);
                                                tree.rename_node(d.id, d.text);

                                            },
                                            error: function (d) {
                                                alert("An error occurred");
                                                tree.refresh();
                                            }
                                        });

                                    }).catch(swal.noop);

                                }
                            },
                            "Rename": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "{{trans_choice('general.edit',1)}}",
                                "action": function (obj) {
                                    if ($node.id == 1) {
                                        alert("Root node can not be Edited")
                                    } else {
                                        //tree.edit($node);
                                        $.ajax({
                                            type: 'GET',
                                            url: "{!!  url('tag') !!}/" + $node.id + "/tag_data",
                                            dataType: "json",
                                            success: function (d) {
                                                $('#name').val(d.name);
                                                $('#notes').val(d.notes);
                                                $('#edit_tag_form').attr("action","{{url('tag/')}}/"+$node.id+"/update");
                                                $('#editTag').modal();
                                            },
                                            error: function (d) {
                                                alert("An error occurred");
                                                tree.refresh();
                                            }
                                        });
                                    }

                                }
                            },
                            "Delete": {
                                "separator_before": false,
                                "separator_after": false,
                                "label": "{{trans_choice('general.delete',1)}}",
                                "action": function (obj) {
                                    if ($node.id == 1) {
                                        alert("Root node can not be deleted")
                                    } else {
                                        swal({
                                            title: '{{trans_choice('general.delete',2)}} {{trans_choice('general.tag',1)}}',
                                            text: '{{trans_choice('general.are_you_sure',2)}}',
                                            type: 'warning',
                                            showCancelButton: true,
                                            focusConfirm: false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: '{{trans_choice('general.yes',2)}}',
                                            cancelButtonText: '{{trans_choice('general.no',2)}}',

                                        }).then(function (name) {
                                            window.location = "{{url('tag/')}}/" + $node.id + "/delete";
                                        })
                                    }

                                }
                            }
                        };
                    }
                }
            }).on('select_node.jstree', function (e, data) {

                var i, j, r = [];
                for (i = 0, j = data.selected.length; i < j; i++) {
                    r.push(data.instance.get_node(data.selected[i]).text);
                }
                $('#event_result').html('Selected: ' + r.join(', '));
            });
        })
    </script>
@endsection
