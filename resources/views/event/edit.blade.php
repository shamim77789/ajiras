@extends('layouts.master')
@section('title')
    {{trans_choice('general.edit',1)}} {{trans_choice('general.event',1)}}
@endsection
@section('content')
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.edit',1)}} {{trans_choice('general.event',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('event/'.$event->id.'/update'), 'method' => 'post', 'name' => 'form','class'=>'form-horizontal',"enctype"=>"multipart/form-data")) !!}

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                {!! Form::select('branch_id',$branches,$event->member_id, array('class' => 'form-control select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
            </div>
            </div>
            <div class="form-group">
                {!! Form::label('name',trans_choice('general.event',1).' '.trans_choice('general.name',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('name',$event->name, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('starts_on',trans_choice('general.starts_on',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-4" id="startDateDiv">
                    {!! Form::text('start_date',$event->start_date, array('class' => 'form-control date-picker', 'id'=>"start_date",'required'=>'required')) !!}
                </div>
                <div class="col-sm-4" id="startTimeDiv">
                    {!! Form::text('start_time',$event->start_time, array('class' => 'form-control time-picker','id'=>'start_time')) !!}
                </div>
            </div>
            <div class="form-group" id="endDiv">
                {!! Form::label('end_date',trans_choice('general.ends_on',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-4" id="endDateDiv">
                    {!! Form::text('end_date',$event->end_date, array('class' => 'form-control date-picker', 'id'=>"end_date",'required'=>'required')) !!}
                </div>
                <div class="col-sm-4" id="startTimeDiv">
                    {!! Form::text('end_time',$event->end_time, array('class' => 'form-control time-picker','id'=>'end_time')) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-5">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="all_day" value="1"
                                   id="all_day" @if($event->all_day==1) checked @endif> {{ trans('general.all_day') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label"></label>
                <div class="col-sm-5">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="recurring" value="1"
                                   id="recurring" @if($event->recurring==1) checked @endif> {{ trans('general.recurring') }}
                        </label>
                    </div>
                </div>
            </div>
            <div id="recurDiv">
                <div class="form-group">
                    {!! Form::label('recur_frequency',trans_choice('general.recur_frequency',1),array('class'=>'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::number('recur_frequency',$event->recur_frequency, array('class' => 'form-control', 'placeholder'=>"",'id'=>'recurF')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('recur_type',trans_choice('general.recur_type',1),array('class'=>'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::select('recur_type', array('day'=>trans_choice('general.day',1).'(s)','week'=>trans_choice('general.week',1).'(s)','month'=>trans_choice('general.month',1).'(s)','year'=>trans_choice('general.year',1).'(s)'),$event->recur_type, array('class' => 'form-control','id'=>'recurT')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('recur_start_date',trans_choice('general.recur_starts',1),array('class'=>'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('recur_start_date',$event->recur_start_date, array('class' => 'form-control date-picker','id'=>'recur_start_date')) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('recur_end_date',trans_choice('general.recur_ends',1),array('class'=>'col-sm-4 control-label')) !!}
                    <div class="col-sm-8">
                        {!! Form::text('recur_end_date',$event->recur_end_date, array('class' => 'form-control date-picker','id'=>'recur_end_date')) !!}                            </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('event_calendar_id',trans_choice('general.calendar',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::select('event_calendar_id',$calendars,$event->event_calendar_id, array('class' => 'form-control select2', 'placeholder'=>"")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('event_location_id',trans_choice('general.location',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::select('event_location_id',$locations,$event->event_location_id, array('class' => 'form-control select2', 'placeholder'=>"")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('featured_image',trans_choice('general.featured_image',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::file('featured_image', array('class' => 'form-control')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('cost',trans_choice('general.cost',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('cost',$event->cost, array('class' => 'form-control', 'placeholder'=>"Leave blank if it's not charged")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('latitude',trans_choice('general.latitude',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('latitude',$event->latitude, array('class' => 'form-control', 'placeholder'=>"")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('longitude',trans_choice('general.longitude',1),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::text('longitude',$event->longitude, array('class' => 'form-control', 'placeholder'=>"")) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'col-sm-4 control-label')) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('notes',$event->notes, array('class' => 'form-control tinymce','rows'=>'3')) !!}
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
    {!! Form::close() !!}
    <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <script>
        $(document).ready(function (e) {
            if ($("#all_day").attr('checked')) {
                $('#start_time').hide();
                $('#end_time').hide();
                $('#start_time').removeAttr('required');
                $('#end_time').removeAttr('required');
                $('#startDateDiv').addClass('col-md-8');
                $('#endDateDiv').addClass('col-md-8');
            } else {
                $('#start_time').show();
                $('#end_time').show();
                $('#start_time').attr('required', 'required');
                $('#end_time').attr('required', 'required');
                $('#startDateDiv').removeClass('col-md-8');
                $('#endDateDiv').removeClass('col-md-8');
            }
            $("#all_day").on('ifChecked', function (e) {
                $('#start_time').hide();
                $('#end_time').hide();
                $('#start_time').removeAttr('required');
                $('#startDateDiv').addClass('col-md-8');
                $('#endDateDiv').addClass('col-md-8');
                $('#end_time').removeAttr('required');
            });
            $("#all_day").on('ifUnchecked', function (e) {
                $('#start_time').show();
                $('#end_time').show();
                $('#start_time').attr('required', 'required');
                $('#startDateDiv').removeClass('col-md-8');
                $('#endDateDiv').removeClass('col-md-8');
                $('#end_time').attr('required', 'required');
            });
            $("#recurring").on('ifChecked', function (e) {
                $('#recurDiv').show();
                $('#recurT').attr('required', 'required');
                $('#recur_start_date').attr('required', 'required');
                $('#recurF').attr('required', 'required');

            });
            $("#recurring").on('ifUnchecked', function (e) {
                $('#recurDiv').hide();
                $('#recurT').removeAttr('required');
                $('#recur_start_date').removeAttr('required');
                $('#recurF').removeAttr('required');
            });
            if ($("#recurring").attr('checked')) {
                $('#recurDiv').show();
                $('#recurT').attr('required', 'required');
                $('#recur_start_date').attr('required', 'required');
                $('#recurF').attr('required', 'required');
            } else {
                $('#recurDiv').hide();
                $('#recurT').removeAttr('required');
                $('#recur_start_date').removeAttr('required');
                $('#recurF').removeAttr('required');
            }

        })
    </script>
@endsection

