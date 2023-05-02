@extends('layouts.master')
@section('title')
    {{trans_choice('general.member',1)}} {{trans_choice('general.detail',2)}}
@endsection
@section('content')
    <div class="box box-widget">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-sm-3">
                    <div class="user-block">
                        @if(!empty($member->photo))
                            <a href="{{asset('uploads/'.$member->photo)}}" class="fancybox"> <img class="img-circle"
                                                                                                  src="{{asset('uploads/'.$member->photo)}}"
                                                                                                  alt="user image"/></a>
                        @else
                            <img class="img-circle"
                                 src="{{asset('assets/dist/img/user.png')}}"
                                 alt="user image"/>
                        @endif
                        <span class="username">{{$member->first_name}} {{$member->middle_name}} {{$member->last_name}}</span>
                        <span class="description" style="font-size:13px; color:#000000">
                            <br>
                                <a href="{{url('member/'.$member->id.'/edit')}}">{{trans_choice('general.edit',1)}}</a><br>

                            {{\Illuminate\Support\Carbon::now()->diffInYears(\Illuminate\Support\Carbon::parse($member->dob))}} {{trans_choice('general.year',2)}}
                            </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <ul class="list-unstyled">
                        <li>
                            <b>{{trans_choice('general.gender',1)}}:</b>
                            @if($member->gender=="male")
                                {{trans_choice('general.male',1)}}
                            @endif
                            @if($member->gender=="female")
                                {{trans_choice('general.female',1)}}
                            @endif
                            @if($member->gender=="unknown")
                                {{trans_choice('general.unknown',1)}}
                            @endif
                        </li>
                        <li>
                            <b>{{trans_choice('general.status',1)}}:</b>
                            @if($member->status=="attender")
                                {{trans_choice('general.attender',1)}}
                            @endif
                            @if($member->status=="visitor")
                                {{trans_choice('general.visitor',1)}}
                            @endif
                            @if($member->status=="inactive")
                                {{trans_choice('general.inactive',1)}}
                            @endif
                            @if($member->status=="member")
                                {{trans_choice('general.member',1)}}
                            @endif
                            @if($member->status=="unknown")
                                {{trans_choice('general.unknown',1)}}
                            @endif
                        </li>
                        <li>
                            <b>{{trans_choice('general.marital_status',1)}}:</b>
                            @if($member->marital_status=="single")
                                {{trans_choice('general.single',1)}}
                            @endif
                            @if($member->marital_status=="divorced")
                                {{trans_choice('general.divorced',1)}}
                            @endif
                            @if($member->marital_status=="widowed")
                                {{trans_choice('general.widowed',1)}}
                            @endif
                            @if($member->marital_status=="engaged")
                                {{trans_choice('general.engaged',1)}}
                            @endif
                            @if($member->marital_status=="separated")
                                {{trans_choice('general.separated',1)}}
                            @endif
                            @if($member->marital_status=="married")
                                {{trans_choice('general.married',1)}}
                            @endif
                            @if($member->marital_status=="unknown")
                                {{trans_choice('general.unknown',1)}}
                            @endif
                        </li>
                        <a data-toggle="collapse" data-parent="#accordion" href="#viewFiles">
                            {{trans_choice('general.view',1)}} {{trans_choice('general.member',1)}} {{trans_choice('general.file',2)}}
                        </a>

                        <div id="viewFiles" class="panel-collapse collapse">
                            <div class="box-body">
                                <ul class="no-margin" style="font-size:12px; padding-left:10px">

                                    @foreach(unserialize($member->files) as $key=>$value)
                                        <li><a href="{!!asset('uploads/'.$value)!!}"
                                               target="_blank">{!!  $value!!}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <li>
                            <small>{{$member->notes}}</small>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="list-unstyled">
                        <li><b>{{trans_choice('general.email',1)}}:</b> <a
                                    onclick="javascript:window.open('mailto:{{$member->email}}', 'mail');event.preventDefault()"
                                    href="mailto:{{$member->email}}">{{$member->email}}</a>

                            <div class="btn-group-horizontal"><a type="button" class="btn-xs bg-red"
                                                                 href="{{url('communication/email/create?member_id='.$member->id)}}">{{trans_choice('general.send',1)}}
                                    {{trans_choice('general.email',1)}}</a></div>
                        </li>
                        <li><b>{{trans_choice('general.mobile_phone',1)}}:</b> {{$member->mobile_phone}}
                            <div class="btn-group-horizontal"><a type="button" class="btn-xs bg-red"
                                                                 href="{{url('communication/sms/create?member_id='.$member->id)}}">{{trans_choice('general.send',1)}}
                                    {{trans_choice('general.sms',1)}}</a></div>
                        </li>
                        <li><b>{{trans_choice('general.home_phone',1)}}:</b> {{$member->home_phone}}</li>
                        <li><b>{{trans_choice('general.work_phone',1)}}:</b> {{$member->work_phone}}</li>
                        <li><b>{{trans_choice('general.address',1)}}:</b> {{$member->address}}</li>
                        <li><b>Member Number:</b> {{$member->member_number}}</li>
						<li><b>Groups :</b>
						<ul>
							@if(!empty($group_members))
								@foreach($group_members as $value)
									<li>{{$value->group_name}}</li>
								@endforeach
							@else
								<li>No Group Selected</li>
							@endif
						</ul>
						</li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <ul class="list-unstyled">
                        <li><b>{{trans_choice('general.custom_field',2)}}</b></li>
						@if(!empty($member->custom_fields_label))
						<?php
							$custom_field_label_array = array();
							$custom_field_array = array();
						
							$custom_field_label_array = explode(',',$member->custom_fields_label);
							$custom_field_array = explode(',',$member->custom_fields);
						?>
						@foreach($custom_field_label_array as $key => $value)
						<li>
							<strong>{{$custom_field_label_array[$key]}}:</strong>{{$custom_field_array[$key]}}
						</li>
						@endforeach						
						@endif
						
<!--						@foreach($custom_fields as $key)
                            <li>
                                @if(!empty($key->custom_field))
                                    <strong>{{$key->custom_field->name}}:</strong>
                                @endif
                                @if($key->custom_field->field_type=="checkbox")
                                    @foreach(unserialize($key->name) as $v=>$k)
                                        {{$k}}<br>
                                    @endforeach
                                @else
                                    {{$key->name}}
                                @endif
                            </li>
                        @endforeach
 -->
                    </ul>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-9">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-info dropdown-toggle margin" data-toggle="dropdown">
                            {{trans_choice('general.member',1)}} {{trans_choice('general.statement',1)}}
                            <span class="fa fa-caret-down"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{url('member/'.$member->id.'/statement/print')}}"
                                   target="_blank">{{trans_choice('general.print',1)}} {{trans_choice('general.statement',1)}}</a>
                            </li>
                            <li>
                                <a href="{{url('member/'.$member->id.'/statement/pdf')}}"
                                   target="_blank">{{trans_choice('general.download',1)}} {{trans_choice('general.in',1)}} {{trans_choice('general.pdf',1)}}</a>
                            </li>
                            <li>
                                <a href="{{url('member/'.$member->id.'/statement/email')}}">{{trans_choice('general.email',1)}}
                                    {{trans_choice('general.statement',1)}}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="pull-left">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tags" data-toggle="tab"
                                          aria-expanded="false"><i
                                    class="fa fa-tags"></i> {{trans_choice('general.tag',2)}}</a>
                    </li>
                    <li class=""><a href="#attendance" data-toggle="tab"
                                    aria-expanded="false"><i
                                    class="fa fa-calendar"></i> {{trans_choice('general.attendance',1)}}</a>
                    </li>
                    <li class=""><a href="#contributions" data-toggle="tab"
                                    aria-expanded="false"><i
                                    class="fa fa-money"></i> {{trans_choice('general.contribution',2)}}</a>
                    </li>
                    <li class=""><a href="#pledges" data-toggle="tab"
                                    aria-expanded="false"><i
                                    class="fa fa-hand-lizard-o"></i> {{trans_choice('general.pledge',2)}}</a>
                    </li>
                    <li class=""><a href="#family" data-toggle="tab"
                                    aria-expanded="false"><i
                                    class="fa fa-group"></i> {{trans_choice('general.family',1)}}</a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="tab-pane active" id="tags">

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{trans_choice('general.name',1)}}</th>
                                    <th>{{trans_choice('general.note',2)}}</th>
                                </tr>
                                </thead>
                                @foreach($member->tags as $key)
                                    @if(!empty($key->tag))
                                        <tr>
                                            <td><a href="{{url('tag/'.$key->tag->id.'/show')}}">{{$key->tag->name}}</a>
                                            </td>
                                            <td>{!! $key->tag->notes!!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane " id="attendance">
                        <div class="table-responsive">
                            <table class="table table-striped data-table">
                                <thead>
                                <tr>
                                    <th>{{trans_choice('general.name',1)}}</th>
                                    <th>{{trans_choice('general.date',2)}}</th>
                                </tr>
                                </thead>
                                @foreach($member->attendance as $key)
                                    @if(!empty($key->event))
                                        <tr>
                                            <td>
                                                <a href="{{url('event/'.$key->event->id.'/show')}}">{{$key->event->name}}</a>
                                            </td>
                                            <td>{!! $key->date!!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="contributions">
                        <div class="table-responsive">
                            <table id="contributions-data-table"
                                   class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>{{trans_choice('general.batch',1)}}</th>
                                    <th>{{trans_choice('general.amount',1)}}</th>
                                    <th>{{trans_choice('general.method',1)}}</th>
                                    <th>{{trans_choice('general.date',1)}}</th>
                                    <th>{{trans_choice('general.note',2)}}</th>
                                    <th>{{trans_choice('general.file',2)}}</th>
                                    <th>{{ trans_choice('general.action',1) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $contributions = 0;
                                ?>
                                @foreach($member->contributions as $key)
                                    <?php
                                    $contributions = $contributions + $key->amount;
                                    ?>
                                    <tr>
                                        <td>
                                            @if(!empty($key->batch))
                                                <a href="{{url('contribution/batch/'.$key->batch->id.'/show')}}">
                                                    {{$key->batch->id}}
                                                    @if(!empty($key->batch->name))
                                                        - {{$key->batch->name}}
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                                {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                                {{number_format($key->amount,2)}}
                                            @else
                                                {{number_format($key->amount,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($key->payment_method))
                                                {{$key->payment_method->name}}
                                            @endif
                                        </td>
                                        <td>{{ $key->date }}</td>

                                        <td>{{ $key->notes }}</td>
                                        <td>
                                            <ul class="">
                                                @foreach(unserialize($key->files) as $k=>$value)
                                                    <li><a href="{!!asset('uploads/'.$value)!!}"
                                                           target="_blank">{!!  $value!!}</a></li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-flat dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    {{ trans('general.choose') }} <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    @if(Sentinel::hasAccess('contributions.update'))
                                                        <li><a href="{{ url('contribution/'.$key->id.'/edit') }}"><i
                                                                        class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('contributions.delete'))
                                                        <li><a href="{{ url('contribution/'.$key->id.'/delete') }}"
                                                               class="delete"><i
                                                                        class="fa fa-trash"></i> {{ trans('general.delete') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td><b>{{ trans('general.total') }}</b></td>
                                    <td colspan="6">
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <b>{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                                {{number_format($contributions,2)}}</b>
                                        @else
                                            <b>{{number_format($contributions,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</b>
                                        @endif
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="pledges">
                        <div class="table-responsive">
                            <table id="pledges-data-table" class="table table-bordered table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>{{trans_choice('general.campaign',1)}}</th>
                                    <th>{{trans_choice('general.amount',1)}}</th>
                                    <th>{{trans_choice('general.date',1)}}</th>
                                    <th>{{trans_choice('general.note',2)}}</th>
                                    <th>{{ trans_choice('general.action',1) }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $pledged = 0;
                                $paid = 0;
                                ?>
                                @foreach($member->pledges as $key)
                                    <?php
                                    $pledged = $pledged + $key->amount;
                                    $paid = $paid + \App\Models\PledgePayment::where('pledge_id',
                                            $key->id)->sum('amount');
                                    ?>
                                    <tr>
                                        <td>
                                            @if(!empty($key->campaign))
                                                <a href="{{url('pledge/campaign/'.$key->campaign->id.'/show')}}">
                                                    {{$key->campaign->id}}
                                                    @if(!empty($key->campaign->name))
                                                        - {{$key->campaign->name}}
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <b>{{ trans_choice('general.pledged',1) }}:</b>
                                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                                {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                                {{number_format($key->amount,2)}}
                                            @else
                                                {{number_format($key->amount,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                            @endif
                                            <br>
                                            <b>{{ trans_choice('general.paid',1) }}:</b>
                                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                                {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                                {{number_format(\App\Models\PledgePayment::where('pledge_id',$key->id)->sum('amount'),2)}}
                                            @else
                                                {{number_format(\App\Models\PledgePayment::where('pledge_id',$key->id)->sum('amount'),2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                            @endif
                                            @if($key->recurring==1)
                                                <span class="label label-success" data-toggle="tooltip"
                                                      title="{{trans_choice('general.recurring',1)}}"> <i
                                                            class="fa fa-refresh"></i> </span>
                                            @endif
                                        </td>

                                        <td>{{ $key->date }}</td>
                                        <td>{{ $key->notes }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-flat dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    {{ trans('general.choose') }} <span class="caret"></span>
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                    @if(Sentinel::hasAccess('pledges.update'))
                                                        @if(\App\Helpers\GeneralHelper::pledge_amount_due($key->id)>0)
                                                            <li>
                                                                <a href="{{ url('pledge/'.$key->id.'/payment/create') }}"><i
                                                                            class="fa fa-plus"></i>
                                                                    {{ trans('general.add') }} {{ trans_choice('general.payment',1) }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if(Sentinel::hasAccess('pledges.view'))
                                                        <li><a href="{{ url('pledge/'.$key->id.'/payment/data') }}"><i
                                                                        class="fa fa-money"></i> {{ trans('general.view') }} {{ trans_choice('general.payment',2) }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('pledges.update'))
                                                        <li><a href="{{ url('pledge/'.$key->id.'/edit') }}"><i
                                                                        class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if(Sentinel::hasAccess('pledges.delete'))
                                                        <li><a href="{{ url('pledge/'.$key->id.'/delete') }}"
                                                               class="delete"><i
                                                                        class="fa fa-trash"></i> {{ trans('general.delete') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td><b>{{ trans('general.total') }}</b></td>
                                    <td colspan="4">
                                        <b>{{ trans_choice('general.pledged',1) }}:</b>
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <b>{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                                {{number_format($pledged,2)}}</b>
                                        @else
                                            <b>{{number_format($pledged,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</b>
                                        @endif
                                        <br>
                                        <b>{{ trans_choice('general.paid',1) }}:</b>
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            <b>{{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                                {{number_format($paid,2)}}</b>
                                        @else
                                            <b>{{number_format($paid,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}</b>
                                        @endif
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane " id="family">
                        <div class="btn-group-horizontal">
                            @if(empty($member->family))
                                <a type="button" class="btn btn-success margin delete"
                                   href="{{url('member/'.$member->id.'/family/create')}}">{{trans_choice('general.create',1)}}
                                    {{trans_choice('general.family',1)}}</a>
                            @else
                                <a type="button" class="btn btn-info margin"
                                   href="#" data-toggle="modal"
                                   data-target="#addFamilyMember">{{trans_choice('general.add',1)}}
                                    {{trans_choice('general.family',1)}} {{trans_choice('general.member',1)}}</a>
                                <div class="modal fade" id="addFamilyMember">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">*</span></button>
                                                <h4 class="modal-title">{{trans_choice('general.add',1)}} {{trans_choice('general.member',1)}}</h4>
                                            </div>
                                            {!! Form::open(array('url' => url('member/'.$member->family->id.'/family/store_family_member'),'method'=>'post')) !!}
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!!  Form::label('member_id',trans_choice('general.member',1),array('class'=>' control-label')) !!}
                                                        {!! Form::select('member_id',$members,null,array('class'=>' select2','placeholder'=>'','required'=>'required')) !!}
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        {!!  Form::label( 'family_role',trans_choice('general.role',1),array('class'=>' control-label')) !!}
                                                        {!! Form::select('family_role',['adult'=>trans_choice('general.adult',1),'spouse'=>trans_choice('general.spouse',1),'head'=>trans_choice('general.head',1),'child'=>trans_choice('general.child',1),'unassigned'=>trans_choice('general.unassigned',1)],null,array('class'=>'form-control','placeholder'=>'','required'=>'required')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit"
                                                        class="btn btn-info">{{trans_choice('general.save',1)}}</button>
                                                <button type="button" class="btn default"
                                                        data-dismiss="modal">{{trans_choice('general.close',1)}}</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                            @endif

                        </div>
                        @if(!empty($member->family))
                            <h3>{{trans_choice('general.the',1)}} {{$member->family->name}} {{trans_choice('general.family',1)}}</h3>
                            <div class="table-responsive">
                                <table class="table table-striped data-table">
                                    <thead>
                                    <tr>
                                        <th>{{trans_choice('general.name',1)}}</th>
                                        <th>{{trans_choice('general.role',1)}}</th>
                                        <th>{{trans_choice('general.action',1)}}</th>
                                    </tr>
                                    </thead>
                                    @foreach(\App\Models\FamilyMember::where('family_id',$member->family->id)->get() as $key)
                                        @if(!empty($key->member))
                                            <tr>
                                                <td>
                                                    <a href="{{url('member/'.$key->member->id.'/show')}}">{{$key->member->first_name}} {{$key->member->middle_name}} {{$key->member->last_name}}</a>
                                                </td>
                                                <td>
                                                    @if($key->family_role=="adult")
                                                        {{trans_choice('general.adult',1)}}
                                                    @endif
                                                    @if($key->family_role=="spouse")
                                                        {{trans_choice('general.spouse',1)}}
                                                    @endif
                                                    @if($key->family_role=="head")
                                                        {{trans_choice('general.head',1)}}
                                                    @endif
                                                    @if($key->family_role=="unassigned")
                                                        {{trans_choice('general.unassigned',1)}}
                                                    @endif
                                                    @if($key->family_role=="child")
                                                        {{trans_choice('general.child',1)}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-info btn-flat dropdown-toggle"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                            {{ trans('general.choose') }} <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" role="menu">

                                                            @if(Sentinel::hasAccess('members.update'))
                                                                <li>
                                                                    <a href="#"
                                                                       data-href="{{ url('member/'.$key->id.'/family/edit_family_member') }}"
                                                                       data-toggle="modal"
                                                                       data-target="#editFamilyMember"><i
                                                                                class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if(Sentinel::hasAccess('members.delete'))
                                                                @if($member->family->member_id!=$key->member_id)
                                                                    <li>
                                                                        <a href="{{ url('member/'.$key->id.'/family/delete_family_member') }}"
                                                                           class="delete"><i
                                                                                    class="fa fa-trash"></i> {{ trans('general.remove') }}
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                                <div class="modal" id="editFamilyMember">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <script>
                                    $('#editFamilyMember').on('shown.bs.modal', function (e) {
                                        var url = $(e.relatedTarget).data('href');
                                        $.ajax({
                                            type: 'GET',
                                            url: url,
                                            success: function (data) {
                                                $(e.currentTarget).find(".modal-content").html(data);
                                            }
                                        });
                                    })
                                </script>
                            </div>
                        @endif
                        @foreach($member->families as $family)
                            @if(empty($member->family))
                                @if(!empty($family->family))
                                    <h3>{{trans_choice('general.the',1)}} {{$family->family->name}} {{trans_choice('general.family',1)}}</h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped data-table">
                                            <thead>
                                            <tr>
                                                <th>{{trans_choice('general.name',1)}}</th>
                                                <th>{{trans_choice('general.role',1)}}</th>
                                                <th>{{trans_choice('general.action',1)}}</th>
                                            </tr>
                                            </thead>
                                            @foreach(\App\Models\FamilyMember::where('family_id',$family->family->id)->get() as $key)
                                                @if(!empty($key->member))
                                                    <tr>
                                                        <td>
                                                            <a href="{{url('member/'.$key->member->id.'/show')}}">{{$key->member->first_name}} {{$key->member->middle_name}} {{$key->member->last_name}}</a>
                                                        </td>
                                                        <td>
                                                            @if($key->family_role=="adult")
                                                                {{trans_choice('general.adult',1)}}
                                                            @endif
                                                            @if($key->family_role=="spouse")
                                                                {{trans_choice('general.spouse',1)}}
                                                            @endif
                                                            @if($key->family_role=="head")
                                                                {{trans_choice('general.head',1)}}
                                                            @endif
                                                            @if($key->family_role=="unassigned")
                                                                {{trans_choice('general.unassigned',1)}}
                                                            @endif
                                                            @if($key->family_role=="child")
                                                                {{trans_choice('general.child',1)}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                        class="btn btn-info btn-flat dropdown-toggle"
                                                                        data-toggle="dropdown" aria-expanded="false">
                                                                    {{ trans('general.choose') }} <span
                                                                            class="caret"></span>
                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-right"
                                                                    role="menu">

                                                                    @if(Sentinel::hasAccess('members.update'))
                                                                        <li>
                                                                            <a href="#"
                                                                               data-href="{{ url('member/'.$key->id.'/family/edit_family_member') }}"
                                                                               data-toggle="modal"
                                                                               data-target="#editFamilyMember"><i
                                                                                        class="fa fa-edit"></i> {{ trans('general.edit') }}
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(Sentinel::hasAccess('members.delete'))
                                                                        @if($family->family->member_id!=$key->member_id)
                                                                            <li>
                                                                                <a href="{{ url('member/'.$key->id.'/family/delete_family_member') }}"
                                                                                   class="delete"><i
                                                                                            class="fa fa-trash"></i> {{ trans('general.remove') }}
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                        <div class="modal" id="editFamilyMember">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <script>
                                            $('#editFamilyMember').on('shown.bs.modal', function (e) {
                                                var url = $(e.relatedTarget).data('href');
                                                $.ajax({
                                                    type: 'GET',
                                                    url: url,
                                                    success: function (data) {
                                                        $(e.currentTarget).find(".modal-content").html(data);
                                                    }
                                                });
                                            })
                                        </script>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
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
        $('#contributions-data-table').DataTable({
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
            "order": [[3, "desc"]],
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
        $('#pledges-data-table').DataTable({
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
                {"orderable": false, "targets": [4]}
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