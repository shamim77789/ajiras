@extends('layouts.master')
@section('title')
    Add Deduction
@endsection

@section('content')
    <div class="box box-primary" id="app">
        <div class="box-header with-border">
            <h3 class="box-title">Add Deduction</h3>

            <div class="box-tools pull-right">

            </div>
        </div>
        {!! Form::open(array('url' => route('deduction.store'), 'method' => 'post','class'=>'', 'name' => 'form',"enctype"=>"multipart/form-data")) !!}
        <div class="box-body">
            <div class="form-group">
                <label class="control-label">Select Branch</label>
                <select name="branch" class="branches form-control select2">
                    <option value="">select branch---</option>
                    @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="control-label">Deduction Type</label>
                <input type="text" name="deduction_type" class="form-control">
            </div>

            <div class="form-group">
                <label class="control-label">Amount Type</label>
				<select class="form-control amount_type" name="amount_type">
					<option value="1">Fix</option>
					<option value="2">Percentage</option>
				</select>
            </div>
            <div class="form-group">
                <label class="control-label">Amount</label>
                <input type="number" name="amount" class="form-control">
            </div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary margin pull-right" name="submit">Submit</button>
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

