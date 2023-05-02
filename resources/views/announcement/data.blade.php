@extends('layouts.master')
@section('title') {{ trans_choice('general.announcement',2) }}
@endsection
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans_choice('general.announcement',1) }}</h3>

            <div class="box-tools pull-right">
                <a href="{{ url('branch/create') }}"
                   class="btn btn-info btn-sm">{{ trans_choice('general.add',1) }} {{ trans_choice('general.announcement',1) }}</a>
            </div>
        </div>
        <div class="box-body">
            <table id="" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>{{ trans_choice('general.name',1) }}</th>
                    <th>{{ trans_choice('general.announcement_type',1) }}</th>
					<th>{{ trans_choice('general.current_date',1) }}</th>
                    <th>{{ trans_choice('general.planning_date',1) }}</th>
                    <th>{{ trans_choice('general.action',1) }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $key)
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td>{{ $key->announcement_types->name }}</td>
						<td>{{date('F j, Y',strtotime($key->current_date))}}</td>
						<td>{{date('F j, Y',strtotime($key->announcement_date))}}</td>
						<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-flat dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">
                                    {{ trans('general.choose') }} <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ url('announcement/'.$key->id.'/show') }}"><i
                                                    class="fa fa-search"></i> {{ trans_choice('general.detail',2) }} </a></li>
                                    <li><a href="{{ url('announcement/'.$key->id.'/edit') }}"><i
                                                    class="fa fa-edit"></i> {{ trans('general.edit') }} </a></li>
                                    <li><a href="{{ url('announcement/'.$key->id.'/delete') }}"
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
