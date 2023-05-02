@extends('layouts.master')
@section('title')
    {{$tag->name}}
@endsection

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{$tag->name}}- {{count($tag->members)}} {{trans_choice('general.people',1)}}</h3>

            <div class="box-tools pull-right">
                @if(Sentinel::hasAccess('members.create'))
                    <a href="#"
                       class="btn btn-success btn-sm" data-toggle="modal" data-target="#addMember"><i
                                class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}
                    </a>
                @endif
                <a href="#"
                   class="btn btn-info btn-sm" data-toggle="modal" data-target="#smsMember"><i
                            class="fa fa-mobile"></i> {{trans_choice('general.sms',1)}} {{trans_choice('general.member',2)}}
                </a>
                <a href="#"
                   class="btn btn-success btn-sm" data-toggle="modal" data-target="#emailMember"><i
                            class="fa fa-envelope"></i> {{trans_choice('general.email',1)}} {{trans_choice('general.member',2)}}
                </a>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="data-table" class="table table-bordered table-striped table-condensed table-hover">
                <thead>
                <tr style="background-color: #D1F9FF">
                    <th>{{trans_choice('general.name',1)}}</th>
                    <th>{{trans_choice('general.photo',1)}}</th>
                    <th>{{trans_choice('general.phone',1)}}</th>
                    <th>{{trans_choice('general.gender',1)}}</th>
                    <th>{{trans_choice('general.age',1)}}</th>
                    <th>{{trans_choice('general.address',1)}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tag->members as $key)
                    @if(!empty($key->member))
                        <tr>
                            <td>
                                <a href="{{url('member/'.$key->member_id.'/show')}}">{{$key->member->first_name}} {{$key->member->last_name}}</a>
                            </td>
                            <td>
                                @if(!empty($key->member->photo))
                                    <a class="fancybox" rel="group"
                                       href="{{ url(asset('uploads/'.$key->member->photo)) }}"> <img
                                                src="{{ url(asset('uploads/'.$key->member->photo)) }}" width="120"/></a>
                                @else
                                    <img class="img-thumbnail"
                                         src="{{asset('assets/dist/img/user.png')}}"
                                         alt="user image" style="max-height: 100px!important;"/>
                                @endif

                            </td>
                            <td>{{$key->member->mobile_phone}}</td>
                            <td>
                                @if($key->member->gender=="male")
                                    {{trans_choice('general.male',1)}}
                                @endif
                                @if($key->member->gender=="female")
                                    {{trans_choice('general.female',1)}}
                                @endif
                                @if($key->member->gender=="unknown")
                                    {{trans_choice('general.unknown',1)}}
                                @endif
                            </td>
                            <td>
                                @if(!empty($key->member->dob))
                                    {{date("Y-m-d")-$key->member->dob}}
                                @endif
                            </td>
                            <td>{!! $key->member->address !!}</td>
                            <td>
                                @if(Sentinel::hasAccess('members.update'))
                                    <a href="{{ url('tag/'.$tag->id.'/remove_member?id='.$key->id) }}"
                                       class="delete"><i
                                                class="fa fa-trash"></i> {{ trans('general.delete') }} </a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="addMember">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.add',2)}} {{trans_choice('general.member',1)}} {{trans_choice('general.to',1)}} {{trans_choice('general.tag',1)}}</h4>
                </div>
                {!! Form::open(array('url' => url('tag/add_members'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$tag->id}}" name="tag_id">
                    <div class="form-group">
                        <div class="form-line">
                            {!!  Form::label('members_id',trans_choice('general.member',2),array('class'=>' control-label')) !!}
                            {!! Form::select('members_id[]',$members,null,array('class'=>' select2','required'=>'required','id'=>'members_id','multiple'=>'multiple')) !!}
                        </div>
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
    <div class="modal fade" id="smsMember">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.sms',2)}} {{trans_choice('general.member',2)}}</h4>
                </div>
                {!! Form::open(array('url' => url('tag/sms_members'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$tag->id}}" name="tag_id">
                    <p>In your sms you can use any of the following tags:
                        {firstName}, {lastName}, {address}, {mobilePhone},
                        {homePhone}</p>
                    <p><b>N.B. SMS cannot exceed 420 characters. 1 sms is 160 characters. Please keep your message in
                            that length</b></p>

                    <div class="form-group">
                        {!! Form::label('message',trans_choice('general.message',1),array('class'=>'')) !!}

                        {!! Form::textarea('message',null, array('class' => 'form-control', 'required'=>"required",'rows'=>'3')) !!}

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
    <div class="modal fade" id="emailMember">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.email',1)}} {{trans_choice('general.member',2)}}</h4>
                </div>
                {!! Form::open(array('url' => url('tag/email_members'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$tag->id}}" name="tag_id">
                    <p>In your email you can use any of the following tags:
                        {firstName}, {lastName}, {address}, {mobilePhone},
                        {homePhone}</p>
                    <div class="form-group">
                        {!! Form::label('subject',trans_choice('general.subject',1),array('class'=>'')) !!}
                        {!! Form::text('subject',null, array('class' => 'form-control', 'required'=>"")) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('message',trans_choice('general.message',1),array('class'=>'')) !!}

                        {!! Form::textarea('message',null, array('class' => 'form-control tinymce', ''=>"",'rows'=>'3')) !!}

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
            "displayLength": 25,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "order": [[0, "asc"]],
            "columnDefs": [
                {"orderable": false, "targets": [6]}
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

