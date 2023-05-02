@extends('layouts.master')
@section('title')
    {{$event->name}}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{url('event/'.$event->id.'/show')}}" class="list-group-item ">
                    <i class="fa fa-bar-chart"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.overview',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/attender')}}" class="list-group-item">
                    <i class="fa fa-user"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.attender',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/report')}}" class="list-group-item">
                    <i class="fa fa-th"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.report',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/volunteer')}}" class="list-group-item">
                    <i class="fa fa-group"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.volunteer',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/payment')}}" class="list-group-item active">
                    <i class="fa fa-money"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.payment',2)}}
                </a>
                <a href="{{url('event/'.$event->id.'/edit')}}" class="list-group-item">
                    <i class="fa fa-edit"></i>&nbsp; &nbsp; &nbsp; {{trans_choice('general.edit',2)}}
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{trans_choice('general.payment',2)}}</h3>

                    <div class="box-tools pull-right">
                        <a href="{{ url('event/'.$event->id.'/payment/create') }}"
                           class="btn btn-success btn-sm"><i
                                    class="fa fa-plus"></i> {{trans_choice('general.add',1)}} {{trans_choice('general.payment',1)}}
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <h3>
                        <b>{{trans_choice('general.total',1)}} {{trans_choice('general.payment',2)}}:</b>
                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                            {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }} {{ number_format(\App\Models\EventPayment::where('event_id',$event->id)->sum('amount'),2) }}
                        @else
                            {{ number_format(\App\Models\EventPayment::where('event_id',$event->id)->sum('amount'),2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                        @endif
                    </h3>
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered table-condensed table-hover">
                            <thead>
                            <tr style="background-color: #D1F9FF">
                                <th>{{trans_choice('general.id',1)}}</th>
                                <th>{{trans_choice('general.collected_by',1)}}</th>
                                <th>{{trans_choice('general.member',1)}}</th>
                                <th>{{trans_choice('general.payment',1)}} {{trans_choice('general.method',1)}}</th>
                                <th>{{trans_choice('general.amount',1)}}</th>
                                <th>{{trans_choice('general.date',1)}}</th>
                                <th>{{ trans_choice('general.action',1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($event->payments as $key)
                                <tr>
                                    <td>#{{$key->id}}</td>
                                    <td>
                                        @if(!empty($key->user))
                                            <a href="{{url('user/'.$key->user->id.'/show')}}">{{$key->user->first_name}} {{$key->user->last_name}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($key->member))
                                            <a href="{{url('member/'.$key->member->id.'/show')}}">{{$key->member->first_name}} {{$key->member->middle_name}} {{$key->member->last_name}}</a>
                                        @else
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($key->payment_method))
                                            {{$key->payment_method->name}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                            {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }} {{ number_format($key->amount,2) }}
                                        @else
                                            {{ number_format($key->amount,2) }}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                        @endif
                                    </td>
                                    <td>{{$key->date}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                {{ trans('general.choose') }} <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                                @if(Sentinel::hasAccess('events.update'))
                                                    <li><a href="{{ url('event/payment/'.$key->id.'/edit') }}"><i
                                                                    class="fa fa-edit"></i> {{trans_choice('general.edit',2)}}
                                                        </a></li>
                                                @endif
                                                @if(Sentinel::hasAccess('events.update'))
                                                    <li><a href="{{ url('event/payment/'.$key->id.'/delete') }}"
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
                        </table>
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

        $('#data-table').DataTable({
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
            "order": [[5, "desc"]],
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
    </script>
    <script>
        $(document).ready(function (e) {

        })
    </script>
@endsection
