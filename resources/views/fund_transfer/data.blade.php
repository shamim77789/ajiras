@extends('layouts.master')
@section('title') {{trans_choice('general.fund_transfer',1)}}
@endsection
@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{trans_choice('general.fund_transfer',1)}}</h3>

        <div class="box-tools pull-right">
            <a href="{{ url('fund_transfer/create') }}"
               class="btn btn-info btn-sm">{{trans_choice('general.add',1)}}  {{trans_choice('general.fund_transfer',1)}}</a>
        </div>
    </div>
    <div class="box-body">
        <table id="" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>{{ trans_choice('general.fund_transfer',1) }}</th>
                <th>{{ trans_choice('general.date',1) }}</th>
				<th>{{ trans_choice('general.note',1) }}</th>
                <th>{{ trans_choice('general.amount',1) }}</th>
                <th>{{ trans_choice('general.action',1) }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fund_transfer as $key)
                <tr>
                    <td>{{ $key->fund_name }}</td>
                    <td>{{ date('F j, Y',strtotime($key->fund_date)) }}</td>
                    <td>{{ $key->notes }}</td>
					<td>{{ number_format($key->amount,2) }}</td>
					<td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false">
                                {{ trans('general.choose') }} <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('fund_transfer/'.$key->id.'/edit') }}"><i
                                                class="fa fa-edit"></i> {{ trans('general.edit') }} </a></li>
                                <li><a href="{{ url('fund_transfer/'.$key->id.'/delete') }}"
                                       class="delete"><i
                                                class="fa fa-trash"></i> {{ trans('general.delete') }} </a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
@section('footer-scripts')

@endsection
