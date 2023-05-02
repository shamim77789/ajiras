@extends('layouts.master')
@section('title')
    {{trans_choice('general.event',2)}}
@endsection
@section('content')
    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.event',2)}}</h3>

            <div class="box-tools pull-right">
                <a href="#" data-toggle="modal" data-target="#addEvent"
                   class="btn btn-info btn-sm">{{trans_choice('general.add',2)}} {{trans_choice('general.event',1)}}</a>
            </div>
        </div>
        <div class="box-body">
            <div id="calendar"></div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="modal fade" id="addEvent">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">*</span></button>
                    <h4 class="modal-title">{{trans_choice('general.add',2)}} {{trans_choice('general.event',1)}}</h4>
                </div>
                {!! Form::open(array('url' => url('event/store'),'method'=>'post','class'=>'form-horizontal',"enctype"=>"multipart/form-data")) !!}
                <div class="modal-body">
                    <input type="hidden" name="return_url" value="{{Request::url()}}">
                    <div class="form-group">
                        {!! Form::label('branch_id',trans_choice('general.branch',1),array('class'=>' col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('branch_id',$branches,null, array('class' => 'select2','placeholder'=>'','required'=>'required','id'=>'contribution_batch_id')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name',trans_choice('general.event',1).' '.trans_choice('general.name',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::text('name',null, array('class' => 'form-control', 'placeholder'=>"",'required'=>'required')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('starts_on',trans_choice('general.starts_on',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-4" id="startDateDiv">
                            {!! Form::text('start_date',date("Y-m-d"), array('class' => 'form-control date-picker', 'id'=>"start_date",'required'=>'required')) !!}
                        </div>
                        <div class="col-sm-4" id="startTimeDiv">
                            {!! Form::text('start_time',date("H:i"), array('class' => 'form-control time-picker','id'=>'start_time')) !!}
                        </div>
                    </div>
                    <div class="form-group" id="endDiv">
                        {!! Form::label('end_date',trans_choice('general.ends_on',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-4" id="endDateDiv">
                            {!! Form::text('end_date',date("Y-m-d"), array('class' => 'form-control date-picker', 'id'=>"end_date",'required'=>'required')) !!}
                        </div>
                        <div class="col-sm-4" id="startTimeDiv">
                            {!! Form::text('end_time',date("H:i"), array('class' => 'form-control time-picker','id'=>'end_time')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-5">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="all_day" value="1"
                                           id="all_day"> {{ trans('general.all_day') }}
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
                                           id="recurring"> {{ trans('general.recurring') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="recurDiv">
                        <div class="form-group">
                            {!! Form::label('recur_frequency',trans_choice('general.recur_frequency',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::number('recur_frequency',null, array('class' => 'form-control', 'placeholder'=>"",'id'=>'recurF')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('recur_type',trans_choice('general.recur_type',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::select('recur_type', array('day'=>trans_choice('general.day',1).'(s)','week'=>trans_choice('general.week',1).'(s)','month'=>trans_choice('general.month',1).'(s)','year'=>trans_choice('general.year',1).'(s)'),'month', array('class' => 'form-control','id'=>'recurT')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('recur_start_date',trans_choice('general.recur_starts',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('recur_start_date',null, array('class' => 'form-control date-picker','id'=>'recur_start_date')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('recur_end_date',trans_choice('general.recur_ends',1),array('class'=>'col-sm-4 control-label')) !!}
                            <div class="col-sm-8">
                                {!! Form::text('recur_end_date',null, array('class' => 'form-control date-picker','id'=>'recur_end_date')) !!}                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('event_calendar_id',trans_choice('general.calendar',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('event_calendar_id',$calendars,null, array('class' => ' select2', 'placeholder'=>"")) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('event_location_id',trans_choice('general.location',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::select('event_location_id',$locations,null, array('class' => ' select2', 'placeholder'=>"")) !!}
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
                            {!! Form::text('cost',null, array('class' => 'form-control', 'placeholder'=>"Leave blank if it's not charged")) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('latitude',trans_choice('general.latitude',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::text('latitude',null, array('class' => 'form-control', 'placeholder'=>"")) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('longitude',trans_choice('general.longitude',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::text('longitude',null, array('class' => 'form-control', 'placeholder'=>"")) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('longitude',trans_choice('general.contribution_type',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
							<select class="contribution_type form-control" name="contribution_type">
								<option value='0'>Select Contribution Type</option>
								@if(!empty($contribution_type))
								@foreach($contribution_type as $contribution)
								<option value="{{$contribution->id}}" data-fund_id="{{$contribution->fund}}">{{$contribution->name}}</option>
								@endforeach
								@endif
							</select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('fund',trans_choice('general.fund',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
							<select class="fund form-control" name="fund">
								<option value='0'>Select Fund</option>
								@if(!empty($funds))
									@foreach($funds as $fund)
										<option value="{{$fund->id}}">{{$fund->name}}</option>
									@endforeach
								@endif
							</select>
						</div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('batch',trans_choice('general.batch',1),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
							<select class="batches form-control" name="batches">
								<option value='0'>Select Batch</option>
								@if(!empty($batches))
									@foreach($batches as $batch)
										<option value="{{$batch->id}}">{{$batch->name}}</option>
									@endforeach
								@endif
							</select>
						</div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('notes',trans_choice('general.note',2),array('class'=>'col-sm-4 control-label')) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('notes',null, array('class' => 'form-control tinymce','rows'=>'3')) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="saveEvent"
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
@endsection
@section('footer-scripts')
    <script src="{{ asset('assets/plugins/fullcalendar/fullcalendar.js') }}"></script>
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
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: '{{trans_choice('general.today',1)}}',
                    month: '{{trans_choice('general.month',1)}}',
                    week: '{{trans_choice('general.week',1)}}',
                    day: '{{trans_choice('general.day',1)}}'
                },
                //Random default events
                events: {!! $events !!},
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    if (start.isBefore(moment())) {
                        $('#calendar').fullCalendar('unselect');
                        alert('You cannot add Events to Past dates.')
                        return false;
                    } else {
                        var start_date = $.fullCalendar.moment(start);
                        var end_date = $.fullCalendar.moment(end);
                        prepopulate_new_event_input(start_date, end_date);
                        $('#addEvent').modal('show');
                    }


                }
            });
        })
        function prepopulate_new_event_input(start_date, end_date) {

            var all_day = start_date._ambigTime; // if _ambigTime == true, then event is all day
            var start_time = moment(start_date).format("H:mm");
            var end_time = moment(end_date).format("H:mm");

            if (all_day == true) {
                end_date.subtract(1, 'd');

            }
            start_date = start_date.format("YYYY-MM-DD");
            end_date = end_date.format("YYYY-MM-DD");

            // multi-day all day events
            if (start_date != end_date && all_day) {
                $('#all_day').attr("checked", "checked");
                $('#start_time').hide();
                $('#end_time').hide();
                $('#start_time').removeAttr('required');
                $('#end_time').removeAttr('required');
                $('#startDateDiv').addClass('col-md-8');
                $('#endDateDiv').addClass('col-md-8');
                // events with times
            } else if (!all_day) {
                $('#start_time').val(start_time);
                $('#end_time').val(end_time);
                $('#all_day').val(start_time);
                $('#start_time').show();
                $('#end_time').show();
                $('#start_time').attr('required', 'required');
                $('#end_time').attr('required', 'required');
                $('#startDateDiv').removeClass('col-md-8');
                $('#endDateDiv').removeClass('col-md-8');
            }
            $('#start_date').val(start_date); // set the start date
            $('#end_date').val(end_date); // set the end date

        }
		
		$(document).on('change','.contribution_type',function(){
		
		 var fund_id = $( ".contribution_type option:selected" ).attr('data-fund_id');
			$('.fund').val(fund_id);			
		});
		
    </script>
@endsection
