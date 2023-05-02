@extends('layouts.master')
@section('title')
    {{trans_choice('general.soul_winning',1)}} {{trans_choice('general.detail',2)}}
@endsection
@section('content')
    <div class="box box-widget">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-3">
                    <div class="user-block">
                        @if(!empty( $soul_winning->photo))
                            <a href="{{asset('uploads/'. $soul_winning->photo)}}" class="fancybox"> <img
                                        class="img-circle"
                                        src="{{asset('uploads/'. $soul_winning->photo)}}"
                                        alt="user image"/></a>
                        @else
                            <img class="img-circle"
                                 src="{{asset('assets/dist/img/user.png')}}"
                                 alt="user image"/>
                        @endif
                        <span class="username">{{ $soul_winning->first_name}} {{ $soul_winning->middle_name}} {{ $soul_winning->last_name}}</span>
                        <span class="description" style="font-size:13px; color:#000000">
                            <br>
                                <a href="{{url('soul_winning/'. $soul_winning->id.'/edit')}}">{{trans_choice('general.edit',1)}}</a><br>

                            {{\Illuminate\Support\Carbon::now()->diffInYears(\Illuminate\Support\Carbon::parse( $soul_winning->dob))}} {{trans_choice('general.year',2)}}
                            </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <ul class="list-unstyled">
                        <li>
                            <b>{{trans_choice('general.gender',1)}}:</b>
                            @if( $soul_winning->gender=="male")
                                {{trans_choice('general.male',1)}}
                            @endif
                            @if( $soul_winning->gender=="female")
                                {{trans_choice('general.female',1)}}
                            @endif
                            @if( $soul_winning->gender=="unknown")
                                {{trans_choice('general.unknown',1)}}
                            @endif
                        </li>
                        <li>
                            <b>{{trans_choice('general.status',1)}}:</b>
                            @if( $soul_winning->status=="attender")
                                {{trans_choice('general.attender',1)}}
                            @endif
                            @if( $soul_winning->status=="visitor")
                                {{trans_choice('general.visitor',1)}}
                            @endif
                            @if( $soul_winning->status=="inactive")
                                {{trans_choice('general.inactive',1)}}
                            @endif
                            @if( $soul_winning->status=="member")
                                {{trans_choice('general.member',1)}}
                            @endif
                            @if( $soul_winning->status=="unknown")
                                {{trans_choice('general.unknown',1)}}
                            @endif
                        </li>
                        <li>
                            <b>{{trans_choice('general.marital_status',1)}}:</b>
                            @if( $soul_winning->marital_status=="single")
                                {{trans_choice('general.single',1)}}
                            @endif
                            @if( $soul_winning->marital_status=="divorced")
                                {{trans_choice('general.divorced',1)}}
                            @endif
                            @if( $soul_winning->marital_status=="widowed")
                                {{trans_choice('general.widowed',1)}}
                            @endif
                            @if( $soul_winning->marital_status=="engaged")
                                {{trans_choice('general.engaged',1)}}
                            @endif
                            @if( $soul_winning->marital_status=="separated")
                                {{trans_choice('general.separated',1)}}
                            @endif
                            @if( $soul_winning->marital_status=="married")
                                {{trans_choice('general.married',1)}}
                            @endif
                            @if( $soul_winning->marital_status=="unknown")
                                {{trans_choice('general.unknown',1)}}
                            @endif
                        </li>
                        <a data-toggle="collapse" data-parent="#accordion" href="#viewFiles">
                            {{trans_choice('general.view',1)}} {{trans_choice('general.member',1)}} {{trans_choice('general.file',2)}}
                        </a>

                        <div id="viewFiles" class="panel-collapse collapse">
                            <div class="box-body">
                                <ul class="no-margin" style="font-size:12px; padding-left:10px">

                                    @foreach(unserialize( $soul_winning->files) as $key=>$value)
                                        <li><a href="{!!asset('uploads/'.$value)!!}"
                                               target="_blank">{!!  $value!!}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <li>
                            <small>{{ $soul_winning->notes}}</small>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="list-unstyled">
                        <li><b>{{trans_choice('general.email',1)}}:</b> <a
                                    onclick="javascript:window.open('mailto:{{ $soul_winning->email}}', 'mail');event.preventDefault()"
                                    href="mailto:{{ $soul_winning->email}}">{{ $soul_winning->email}}</a>

                            <div class="btn-group-horizontal"><a type="button" class="btn-xs bg-red"
                                                                 href="{{url('communication/email/create?member_id='. $soul_winning->id)}}">{{trans_choice('general.send',1)}}
                                    {{trans_choice('general.email',1)}}</a></div>
                        </li>
                        <li><b>{{trans_choice('general.mobile_phone',1)}}:</b> {{ $soul_winning->mobile_phone}}
                            <div class="btn-group-horizontal"><a type="button" class="btn-xs bg-red"
                                                                 href="{{url('communication/sms/create?member_id='. $soul_winning->id)}}">{{trans_choice('general.send',1)}}
                                    {{trans_choice('general.sms',1)}}</a></div>
                        </li>
                        <li><b>{{trans_choice('general.home_phone',1)}}:</b> {{ $soul_winning->home_phone}}</li>
                        <li><b>{{trans_choice('general.work_phone',1)}}:</b> {{ $soul_winning->work_phone}}</li>
                        <li><b>{{trans_choice('general.address',1)}}:</b> {{ $soul_winning->address}}</li>
                    </ul>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-3">
                    @if(empty($soul_winning->member_id))
                        <a href="{{url('soul_winning/'. $soul_winning->id.'/convert_to_member')}}"
                           class="btn btn-success delete">{{trans_choice('general.convert_to_member',1)}}
                        </a>
                    @else
                        <a href="{{url('member/'. $soul_winning->member_id.'/show')}}"
                           class="btn btn-info ">{{trans_choice('general.view',1)}} {{trans_choice('general.member',1)}}
                        </a>
                    @endif

                </div>
                <div class="col-sm-9">
                    {{$soul_winning->notes}}
                </div>
            </div>
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
        $('.data-table').DataTable();
    </script>
    <script>
        $(document).ready(function () {
            $('.deletePayment').on('click', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                swal({
                    title: '{{trans_choice('general.are_you_sure',1)}}',
                    text: 'If you delete a payment, a fully paid loan may change status to open.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{trans_choice('general.ok',1)}}',
                    cancelButtonText: '{{trans_choice('general.cancel',1)}}'
                }).then(function () {
                    window.location = href;
                })
            });
        });
    </script>
@endsection