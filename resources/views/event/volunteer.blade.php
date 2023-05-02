@extends('layouts.master')
@section('title')
    {{$event->name}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{url('event/'.$event->id.'/show')}}" class="list-group-item">
                    <i class="fa fa-bar-chart"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.overview',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/attender')}}" class="list-group-item ">
                    <i class="fa fa-user"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.attender',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/report')}}" class="list-group-item">
                    <i class="fa fa-th"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.report',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/volunteer')}}" class="list-group-item active">
                    <i class="fa fa-group"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.volunteer',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/payment')}}" class="list-group-item">
                    <i class="fa fa-money"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.payment',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/edit')}}" class="list-group-item">
                    <i class="fa fa-edit"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.edit',2)}}
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{trans_choice('general.volunteer',2)}}</h3>

                    <div class="box-tools pull-right">
                        <a href="#"
                           class="btn btn-success btn-sm" data-toggle="modal" data-target="#addVolunteer"><i
                                    class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.volunteer',1)}}
                        </a>
                        <a href="#"
                           class="btn btn-info btn-sm" data-toggle="modal" data-target="#smsVolunteer"><i
                                    class="fa fa-mobile"></i> {{trans_choice('general.sms',1)}} {{trans_choice('general.volunteer',2)}}
                        </a>
                        <a href="#"
                           class="btn btn-success btn-sm" data-toggle="modal" data-target="#emailVolunteer"><i
                                    class="fa fa-envelope"></i> {{trans_choice('general.email',1)}} {{trans_choice('general.volunteer',2)}}
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered table-condensed table-hover">
                            <thead>
                            <tr style="background-color: #D1F9FF">
                                <th>{{trans_choice('general.id',1)}}</th>
                                <th>{{trans_choice('general.name',1)}}</th>
                                <th>{{trans_choice('general.photo',1)}}</th>
                                <th>{{trans_choice('general.phone',1)}}</th>
                                <th>{{trans_choice('general.gender',1)}}</th>
                                <th>{{trans_choice('general.age',1)}}</th>
                                <th>{{ trans_choice('general.action',1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($event->volunteers as $key)
                                @if(!empty($key->member))
                                    <tr>
                                        <td>#{{$key->id}}</td>
                                        <td>
                                            <a href="{{url('member/'.$key->id.'/show')}}">{{$key->member->first_name}} {{$key->member->middle_name}} {{$key->member->last_name}}</a>
                                        </td>
                                        <td>
                                            @if(!empty($key->member->photo))
                                                <a class="fancybox" rel="group"
                                                   href="{{ url(asset('uploads/'.$key->member->photo)) }}"> <img
                                                            src="{{ url(asset('uploads/'.$key->member->photo)) }}"
                                                            width="100"/></a>
                                            @else
                                                <img class="img-thumbnail"
                                                     src="{{asset('assets/dist/img/user.png')}}"
                                                     alt="user image" style="max-height: 70px!important;"/>
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
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    {{ trans('general.choose') }} <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    @if(Sentinel::hasAccess('events.view'))
                                                        <li>
                                                            <a href="#" data-toggle="modal" data-target="#showVolunteer"
                                                               data-id="{{$key->id}}"><i
                                                                        class="fa fa-search"></i> {{trans_choice('general.detail',2)}}
                                                            </a></li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('events.update'))
                                                        <li>
                                                            <a href="{{ url('event/'.$key->id.'/remove_volunteer') }}"
                                                               class="delete"><i
                                                                        class="fa fa-remove"></i> {{trans_choice('general.remove',2)}}
                                                            </a></li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('events.update'))
                                                        <li><a href="#" data-toggle="modal" data-target="#editVolunteer"
                                                               data-id="{{$key->id}}"><i
                                                                        class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addVolunteer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.add',2)}} {{trans_choice('general.volunteer',1)}}</h4>
                </div>
                {!! Form::open(array('url' => url('event/add_volunteer'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$event->id}}" name="event_id">
                    <div class="form-group">
                        <div class="form-line">
                            {!!  Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                            {!! Form::select('member_id',$members,null,array('class'=>' select2','required'=>'required','id'=>'','placeholder'=>'')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-line">
                            {!!  Form::label('roles',trans_choice('general.role',2),array('class'=>' control-label')) !!}
                            {!! Form::select('roles[]',$roles,null,array('class'=>' select2','id'=>'','multiple'=>'multiple')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'')) !!}
                        {!! Form::text('notes',null, array('class' => 'form-control tinymce',''=>'')) !!}
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
    <div class="modal" id="editVolunteer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.edit',2)}} {{trans_choice('general.volunteer',1)}}</h4>
                </div>
                {!! Form::open(array('url' => '','method'=>'post','id'=>'edit_form')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$event->id}}" name="event_id">
                    <div class="form-group">
                        <div class="form-line">
                            {!!  Form::label('roles',trans_choice('general.role',2),array('class'=>' control-label')) !!}
                            {!! Form::select('roles[]',$roles,null,array('class'=>'select2 form-control','id'=>'roles','multiple'=>'multiple')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'')) !!}
                        {!! Form::textarea('notes',null, array('class' => 'form-control tinymce','rows'=>'4','id'=>'notes')) !!}
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
    <div class="modal fade" id="showVolunteer">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="smsVolunteer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.sms',2)}} {{trans_choice('general.member',2)}}</h4>
                </div>
                {!! Form::open(array('url' => url('event/sms_volunteers'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$event->id}}" name="event_id">
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
    <div class="modal fade" id="emailVolunteer">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.email',1)}} {{trans_choice('general.member',2)}}</h4>
                </div>
                {!! Form::open(array('url' => url('event/email_volunteers'),'method'=>'post','id'=>'')) !!}
                <div class="modal-body">
                    <input type="hidden" value="{{$event->id}}" name="event_id">
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
    <script>
        $(document).ready(function (e) {
            if ($(event.target).closest(".mce-window").length) {
                e.stopImmediatePropagation();
            }
        });
        $('#editVolunteer').on('shown.bs.modal', function (e) {
            //clear values
            tinyMCE.activeEditor.setContent("");
            var id = $(e.relatedTarget).data('id');
            var aurl = "{!!  url('event') !!}/" + id + "/update_volunteer";
            $.ajax({
                type: 'GET',
                url: "{!!  url('event') !!}/" + id + "/get_volunteer",
                dataType: "json",
                success: function (data) {
                    tinyMCE.activeEditor.setContent(data.notes);
                    $.each(data.roles, function (index, value) {
                        $(e.currentTarget).find("#roles option:contains('" + value + "')").attr("selected", "selected");
                    });

                    $(e.currentTarget).find("#edit_form").attr('action', aurl);

                }
            });

        });
        $('#showVolunteer').on('shown.bs.modal', function (e) {
            //clear values
            tinyMCE.activeEditor.setContent("");
            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'GET',
                url: "{!!  url('event') !!}/" + id + "/volunteer_detail",
                success: function (data) {
                    $(e.currentTarget).find(".modal-content").html(data);

                }
            });

        });
        $('#editVolunteer').on('hidden.bs.modal', function (e) {
            $(e.currentTarget).find("#roles option").removeAttr("selected");
        })
    </script>
@endsection
