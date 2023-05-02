@extends('layouts.master')
@section('title')
    Update Deduction
@endsection

@section('content')
    <div class="box box-primary" id="app">
        <div class="box-header with-border">
            <h3 class="box-title">Update Deduction</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => route('deduction.update'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <input type="hidden" name="id" value="{{ $deduction->id }}">
            <div class="form-group">
                <label class="control-label">Select Branch</label>
                <select name="branch_id" class="branches form-control select2">
                    <option value="">select branch---</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ ($branch->id == $deduction->branch_id) ? "selected" : "" }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Deduction Type</label>
                <input type="text" name="deduction_type" class="form-control" value="{{ $deduction->deduction_type }}">
            </div>
            <div class="form-group">
                <label class="control-label">Amount Type</label>
				<select class="form-control amount_type" name="amount_type">
					<option value="1" {{ $deduction->amount_type == '1' ? 'selected' : '' }}>Fix</option>
					<option value="2" {{ $deduction->amount_type == '2' ? 'selected' : '' }}>Percentage</option>
				</select>
            </div>
			<div class="form-group">
                <label class="control-label">Amount</label>
                <input type="number" name="amount" class="form-control" value="{{ $deduction->amount }}">
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary margin pull-right" name="submit">Update</button>
        </div>
    {!! Form::close() !!}
    <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection
@section('footer-scripts')
    <script>

    </script>
@endsection

