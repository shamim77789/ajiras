@extends('layouts.master')
@section('title')
    {{trans_choice('general.add',1)}} {{trans_choice('general.fund_transfer',1)}}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans_choice('general.add',1)}} {{trans_choice('general.fund_transfer',1)}}</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => url('fund_transfer/update', $fund_transfer->id), 'method' => 'post', 'class' => 'form-horizontal')) !!}
        <div class="box-body" style="padding-right: 1%;padding-left: 1%;">
            <div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('name','Fund Transfer Name',array('class'=>'control-label')) !!}
					<input class="form-control fund_name" name="fund_name" placeholder="Fund Transfer Name" value="{{$fund_transfer->fund_name}}">
				</div>
            </div>
			<div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('name','Fund Transfer Date',array('class'=>'control-label')) !!}
	                {!! Form::text('fund_date',date('Y-m-d',strtotime($fund_transfer->fund_date)), array('class' => 'form-control date-picker', 'placeholder'=>"",'required'=>'required')) !!}
				</div>
            </div>
			<div class="form-group">
                <div class="col-md-12">
                    <label class="control-label">From</label>
                    <select name="from" class="from form-control select2" required>
                        <option value="">select expense source---</option>
                        <optgroup label="Contribution Type">
                            @foreach($ContributionType as $ctype)
                            <option value="0,{{ $ctype->id }}" {{"0,". $ctype->id == $fund_transfer->from ? 'selected' : ''  }} source="contributiontype">{{ $ctype->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Fund">
                           @foreach($fund as $fu)
                            <option value="1,{{ $fu->id }}" {{"1,". $fu->id == $fund_transfer->from ? 'selected' : ''  }}  source="fund">{{ $fu->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Other Income Type">
                           @foreach($otherIncome as $other)
                            <option value="2,{{ $other->id }}" {{"2,". $other->id == $fund_transfer->from ? 'selected' : ''  }}  source="otherincome">{{ $other->name }}</option>
                           @endforeach
                        </optgroup>
						<optgroup label="Campaign Name">
						@if(!empty($campaign))
							@foreach($campaign as $camp)
								<option value="3,{{$camp->id}}" {{"3,". $other->id == $fund_transfer->from ? 'selected' : ''  }}>{{$camp->name}}</option>
							@endforeach
						@endif
						</optgroup>
						<optgroup label="Campaign Type">
							@foreach($campaign_type as $type)
							<option value="4,{{$type->id}}" {{"4,". $other->id == $fund_transfer->from ? 'selected' : ''  }}>{{$type->name}}</option>
							@endforeach
						</optgroup>						
                    </select>
                </div>
            </div>
			<div class="form-group">
                <div class="col-md-12">
                    <label class="control-label">To</label>
                    <select name="to" class="to form-control select2" required>
                        <option value="">select expense source---</option>
                        <optgroup label="Contribution Type">
                            @foreach($ContributionType as $ctype)
                            <option value="0,{{ $ctype->id }}" {{"0,". $ctype->id == $fund_transfer->to ? 'selected' : ''  }}  source="contributiontype">{{ $ctype->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Fund">
                           @foreach($fund as $fu)
                            <option value="1,{{ $fu->id }}" {{"1,". $fu->id == $fund_transfer->to ? 'selected' : ''  }}  source="fund">{{ $fu->name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Other Income Type">
                           @foreach($otherIncome as $other)
                            <option value="2,{{ $other->id }}" {{"2,". $other->id == $fund_transfer->to ? 'selected' : ''  }}  source="otherincome">{{ $other->name }}</option>
                           @endforeach
                        </optgroup>
						<optgroup label="Campaign Name">
						@if(!empty($campaign))
							@foreach($campaign as $camp)
								<option value="3,{{$camp->id}}"  {{"3,". $other->id == $fund_transfer->to ? 'selected' : ''  }} >{{$camp->name}}</option>
							@endforeach
						@endif
						</optgroup>
						<optgroup label="Campaign Type">
							@foreach($campaign_type as $type)
							<option value="4,{{$type->id}}"  {{"4,". $other->id == $fund_transfer->to ? 'selected' : ''  }} >{{$type->name}}</option>
							@endforeach
						</optgroup>
                    </select>
                </div>
            </div>
			<div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('name','Amount',array('class'=>'control-label')) !!}
					<input type="number" name="amount" class="form-control amount" required value="{{$fund_transfer->amount}}">
				</div>
            </div>
			<div class="form-group">
                <div class="col-md-12">
                    {!! Form::label('name','Notes',array('class'=>'control-label')) !!}
					<textarea class="form-control notes" name="notes">{{$fund_transfer->notes}}</textarea>
				</div>
			</div>
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-primary pull-right">{{trans_choice('general.save',1)}}</button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box -->

@endsection

