@extends('layouts.master')
@section('title')
    {{ trans_choice('general.setting',2) }}
@endsection
@section('content')
    <div class="box box-primary">
        {!! Form::open(array('url' => url('setting/update'), 'method' => 'post', 'name' => 'form','class'=>"form-horizontal","enctype"=>"multipart/form-data")) !!}
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.setting',2) }}</h3>

            <div class="box-tools pull-right">
                <button type="submit" class="btn btn-info">{{ trans('general.save') }}</button>
            </div>
        </div>
        <div class="box-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li><a href="#general" data-toggle="tab">{{ trans('general.general') }}</a></li>
                    <li><a href="#sms" data-toggle="tab">{{ trans('general.sms') }}</a></li>
                    <li><a href="#email_templates"
                           data-toggle="tab">{{ trans_choice('general.email',1) }} {{ trans_choice('general.template',2) }}</a>
                    </li>
                    <li><a href="#sms_templates"
                           data-toggle="tab">{{ trans_choice('general.sms',1) }} {{ trans_choice('general.template',2) }}</a>
                    </li>
                    <li class="active"><a href="#system" data-toggle="tab">{{ trans_choice('general.system',1) }}</a>
                    </li>
                    <li><a href="#payments" data-toggle="tab">{{ trans_choice('general.payment',2) }}</a></li>
                    <li><a href="#update" data-toggle="tab">{{ trans_choice('general.update',2) }}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="general">
                        <div class="form-group">
                            {!! Form::label('company_name',trans('general.company_name'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_name',\App\Models\Setting::where('setting_key','company_name')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_email',trans('general.company_email'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::email('company_email',\App\Models\Setting::where('setting_key','company_email')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_website',trans('general.company_website'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_website',\App\Models\Setting::where('setting_key','company_website')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_address',trans('general.company_address'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::textarea('company_address',\App\Models\Setting::where('setting_key','company_address')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_country',trans('general.country'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_country',\App\Models\Setting::where('setting_key','company_country')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('portal_address',trans('general.portal_address'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('portal_address',\App\Models\Setting::where('setting_key','portal_address')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_currency',trans('general.currency'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('company_currency',\App\Models\Setting::where('setting_key','company_currency')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('currency_symbol',trans('general.currency_symbol'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::text('currency_symbol',\App\Models\Setting::where('setting_key','currency_symbol')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('currency_position',trans('general.currency_position'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::select('currency_position',array('left'=>trans('general.left'),'right'=>trans('general.right')),\App\Models\Setting::where('setting_key','currency_position')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('company_logo',trans('general.company_logo'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                @if(!empty(\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value))
                                    <img src="{{ url(asset('uploads/'.\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value)) }}"
                                         class="img-responsive"/>

                                @endif
                                {!! Form::file('company_logo',array('class'=>'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-info">{{ trans('general.save') }}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="sms">
                        <div class="form-group">
                            {!! Form::label('sms_enabled',trans('general.sms_enabled'),array('class'=>'col-sm-2 control-label')) !!}

                            <div class="col-sm-10">
                                {!! Form::select('sms_enabled',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','sms_enabled')->first()->setting_value,array('class'=>'form-control','required'=>'required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('active_sms',trans('general.active_sms'),array('class'=>'col-sm-2 control-label')) !!}
                            <div class="col-sm-10">
                                {!! Form::select('active_sms',$sms_gateways,\App\Models\Setting::where('setting_key','active_sms')->first()->setting_value,array('class'=>'form-control','placeholder'=>'')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-info">{{ trans('general.save') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="email_templates">

                        <p>You can use any of the following tags: <span class="label label-info">{firstName}</span>
                            <span
                                    class="label label-info">{lastName}</span> <span
                                    class="label label-info">{address}</span>
                            <span class="label label-info">{mobilePhone}</span> <span class="label label-info">{homePhone}</span>
                        </p>

                        <div class="form-group">
                            {!! Form::label('password_reset_subject',trans('general.password_reset_subject'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('password_reset_subject',\App\Models\Setting::where('setting_key','password_reset_subject')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('password_reset_template',trans('general.password_reset_template'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('password_reset_template',\App\Models\Setting::where('setting_key','password_reset_template')->first()->setting_value,array('class'=>'form-control tinymce')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('member_statement_email_subject',trans('general.member_statement_email_subject'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('member_statement_email_subject',\App\Models\Setting::where('setting_key','member_statement_email_subject')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('member_statement_email_template',trans('general.member_statement_email_template'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('member_statement_email_template',\App\Models\Setting::where('setting_key','member_statement_email_template')->first()->setting_value,array('class'=>'form-control tinymce')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('follow_up_email_subject',trans('general.follow_up_email_subject'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('follow_up_email_subject',\App\Models\Setting::where('setting_key','follow_up_email_subject')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('follow_up_email_template',trans('general.follow_up_email_template'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('follow_up_email_template',\App\Models\Setting::where('setting_key','follow_up_email_template')->first()->setting_value,array('class'=>'form-control tinymce')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('volunteer_assignment_email_subject',trans('general.volunteer_assignment_email_subject'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('volunteer_assignment_email_subject',\App\Models\Setting::where('setting_key','volunteer_assignment_email_subject')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('volunteer_assignment_email_template',trans('general.volunteer_assignment_email_template'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('volunteer_assignment_email_template',\App\Models\Setting::where('setting_key','volunteer_assignment_email_template')->first()->setting_value,array('class'=>'form-control tinymce')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="sms_templates">
                        <p>Universal tags to use: <span class="label label-info">{firstName}</span> <span
                                    class="label label-info">{lastName}</span> <span
                                    class="label label-info">{address}</span>
                            <span class="label label-info">{mobilePhone}</span> <span class="label label-info">{homePhone}</span>
                            <span class="label label-info">{paymentAmount}</span>
                            <span class="label label-info">{paymentDate}</span>
                        </p>

                        <div class="form-group">
                            {!! Form::label('follow_up_sms_template',trans('general.follow_up_sms_template'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('follow_up_sms_template',\App\Models\Setting::where('setting_key','follow_up_sms_template')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('payment_sms_template',trans('general.payment_sms_template'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::textarea('payment_sms_template',\App\Models\Setting::where('setting_key','payment_sms_template')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane active" id="system">
                        <div class="form-group">
                            {!! Form::label('enable_cron',trans('general.cron_enabled'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('enable_cron',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','enable_cron')->first()->setting_value,array('class'=>'form-control')) !!}
                                <small>Last
                                    Run:@if(!empty(\App\Models\Setting::where('setting_key','cron_last_run')->first()->setting_value)) {{\App\Models\Setting::where('setting_key','cron_last_run')->first()->setting_value}} @else
                                        Never @endif</small>
                                <br>
                                <small>Cron Url: {{url('cron')}}</small>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('auto_payment_receipt_email',trans('general.auto_payment_receipt_email'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('auto_payment_receipt_email',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','auto_payment_receipt_email')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('auto_payment_receipt_sms',trans('general.auto_payment_receipt_sms'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('auto_payment_receipt_sms',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','auto_payment_receipt_sms')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('email_volunteer_assignment',trans('general.email_volunteer_assignment'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('email_volunteer_assignment',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','email_volunteer_assignment')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('google_maps_key',trans('general.google_maps_key'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('google_maps_key',\App\Models\Setting::where('setting_key','google_maps_key')->first()->setting_value,array('class'=>'form-control','rows'=>'3')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane " id="payments">

                        <div class="form-group">
                            {!! Form::label('enable_online_giving',trans('general.enable_online_giving'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::select('enable_online_giving',array('1'=>trans('general.yes'),'0'=>trans('general.no')),\App\Models\Setting::where('setting_key','enable_online_giving')->first()->setting_value,array('class'=>'form-control')) !!}
                            </div>
                        </div>
                        <div class="form-group" id="paypalDiv">
                            {!! Form::label('paypal_email',trans('general.paypal_email'),array('class'=>'col-sm-3 control-label')) !!}
                            <div class="col-sm-9">
                                {!! Form::text('paypal_email',\App\Models\Setting::where('setting_key','paypal_email')->first()->setting_value,array('class'=>'form-control','id'=>'paypal_email')) !!}
                                <p>Paypal IPN URL:{{url('donation/paypal/ipn')}}</p>

                            </div>
                        </div>

                        <div id="paynowDiv">
                            <div class="form-group">
                                {!! Form::label('paynow_id',trans('general.paynow_id'),array('class'=>'col-sm-3 control-label')) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('paynow_id',\App\Models\Setting::where('setting_key','paynow_id')->first()->setting_value,array('class'=>'form-control','id'=>'paynow_id')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('paynow_key',trans('general.paynow_key'),array('class'=>'col-sm-3 control-label')) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('paynow_key',\App\Models\Setting::where('setting_key','paynow_key')->first()->setting_value,array('class'=>'form-control','id'=>'paynow_key')) !!}
                                </div>
                            </div>
                        </div>
                        <div id="stripeDiv">
                            <div class="form-group">
                                {!! Form::label('stripe_secret_key',trans('general.stripe_secret_key'),array('class'=>'col-sm-3 control-label')) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('stripe_secret_key',\App\Models\Setting::where('setting_key','stripe_secret_key')->first()->setting_value,array('class'=>'form-control','id'=>'paynow_id')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('stripe_publishable_key',trans('general.stripe_publishable_key'),array('class'=>'col-sm-3 control-label')) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('stripe_publishable_key',\App\Models\Setting::where('setting_key','stripe_publishable_key')->first()->setting_value,array('class'=>'form-control','id'=>'paynow_key')) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="update">
                        <div class="form-group">
                            <div class="col-sm-4 text-right">Local Version:</div>

                            <div class="col-sm-4">
                                <span class="label label-primary">{{\App\Models\Setting::where('setting_key','system_version')->first()->setting_value}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 text-right">Server Version:</div>

                            <div class="col-sm-4">
                                <button class="btn btn-info btn-sm" type="button" id="checkUpdate">Check Version
                                </button>
                                <br>
                                <span class="label label-primary" id="serverVersion"></span>
                            </div>
                        </div>
                        <div id="updateMessage"></div>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">{{ trans('general.save') }}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')
    <script>
        $('#checkUpdate').click(function (e) {
            $.ajax({
                type: 'POST',
                url: '{{\App\Models\Setting::where('setting_key','update_url')->first()->setting_value}}',
                dataType: 'json',
                success: function (data) {
                    if ("{!! \App\Models\Setting::where('setting_key','system_version')->first()->setting_value !!}}" < data.version) {
                        swal({
                            title: '{{trans_choice('general.update_available',1)}}<br>v' + data.version,
                            html: data.notes,
                            type: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '{{trans_choice('general.download',1)}}',
                            cancelButtonText: '{{trans_choice('general.cancel',1)}}'
                        }).then(function () {
                            //curl function to download update
                            //notify user that update is in progress, do not navigate from page
                            $('#updateMessage').html("<div class='alert alert-warning'>{{trans_choice('general.do_not_navigate_from_page',1)}}</div>");
                            window.location = "{{url('update/download?url=')}}" + data.url;
                        });
                        $('#serverVersion').html(data.version);
                    } else {
                        swal({
                            title: '{{trans_choice('general.no_update_available',1)}}',
                            text: '{{trans_choice('general.system_is_up_to_date',1)}}',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '{{trans_choice('general.ok',1)}}',
                            cancelButtonText: '{{trans_choice('general.cancel',1)}}'
                        })
                    }
                }
                ,
                error: function (e) {
                    alert("There was an error connecting to the server")
                }
            });
        })
    </script>
@endsection
