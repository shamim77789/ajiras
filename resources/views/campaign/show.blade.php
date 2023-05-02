@extends('layouts.master')
@section('title')
    {{trans_choice('general.campaign',1)}}
    @if(!empty($campaign->name))
        -{{$campaign->name}}
    @else
        - {{$campaign->id}}
    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        @if(!empty($campaign->name))
                            {{$campaign->name}}
                        @else
                            #{{$campaign->id}}
                        @endif
                    </h3>

                    <div class="box-tools pull-right">

                    </div>
                </div>
                <div class="box-body">
                    <table  class="table">
                        <tr>
                            <td><b>{{ trans_choice('general.id',1) }}</b></td>
                            <td>{{ $campaign->id }}</td>
                        </tr>
                        <tr>
                            <td><b>{{ trans_choice('general.date',1) }}</b></td>
                            <td>
                                <b>{{trans_choice('general.start',1).' '.trans_choice('general.date',1)}}
                                    :</b> {{ $campaign->start_date }}<br>
                                <b>{{trans_choice('general.end',1).' '.trans_choice('general.date',1)}}
                                    :</b> {{ $campaign->end_date }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>{{ trans_choice('general.progress',2) }}</b></td>
                            <td>
                                <?php
                                $progress = round((\App\Helpers\GeneralHelper::campaign_total_amount($campaign->id) / $campaign->goal) * 100)
                                ?>
                                <div class="progress progress-sm active" data-toggle="tooltip"
                                     title="{{$progress}}% {{ trans_choice('general.complete',1) }}">
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                                         aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{$progress}}%">
                                        <span class="sr-only">{{$progress}}% {{ trans_choice('general.complete',1) }}</span>
                                    </div>
                                </div>
                                <b>{{ trans_choice('general.goal',1) }}:</b>
                                @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                    {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                    {{number_format($campaign->goal,2)}}
                                @else
                                    {{number_format($campaign->goal,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                @endif
                                <br>
                                <b>{{ trans_choice('general.pledged',1) }}:</b>
                                @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                    {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                    {{number_format(\App\Helpers\GeneralHelper::campaign_pledged_amount($campaign->id),2)}}
                                @else
                                    {{number_format(\App\Helpers\GeneralHelper::campaign_pledged_amount($campaign->id),2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                @endif
                                <br>
                                <b>{{ trans_choice('general.raised',1) }}:</b>
                                @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                    {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                    {{number_format(\App\Helpers\GeneralHelper::campaign_total_amount($campaign->id),2)}}
                                @else
                                    {{number_format(\App\Helpers\GeneralHelper::campaign_total_amount($campaign->id),2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                                @endif
                            </td>
                        </tr>
						<tr>
							<td><b>{{ trans_choice('general.fund',1)}}</b></td>
							<td>{{$fund->fund_name}}</td>
						</tr>
                        <tr>
                            <td><b>{{ trans_choice('general.status',1) }}</b></td>
                            <td>
                                @if($campaign->status==0)
                                    <span class="label label-success">{{ trans_choice('general.open',1) }}</span>
                                @else
                                    <span class="label label-danger">{{ trans_choice('general.closed',1) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><b>{{ trans_choice('general.note',2) }}</b></td>
                            <td>{!!   $campaign->notes !!}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        {{trans_choice('general.pledge',2)}}

                    </h3>

                    <div class="box-tools pull-right">
                        @if(Sentinel::hasAccess('pledges.create'))
                            <a href="{{ url('pledge/create?campaign_id='.$campaign->id) }}"
                               class="btn btn-info btn-sm">{{trans_choice('general.add',1)}} {{trans_choice('general.pledge',1)}} </a>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered table-condensed table-hover">
                            <thead>
                            <tr>
                                <th>{{trans_choice('general.member',1)}}</th>
                                <th>{{trans_choice('general.amount',1)}}</th>
                                <th>{{trans_choice('general.date',1)}}</th>
                                <th>{{trans_choice('general.note',2)}}</th>
                                <th>{{ trans_choice('general.action',1) }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campaign->pledges as $key)
                                <tr>
                                    <td>
                                        @if(!empty($key->member))
                                            <a href="{{url('member/'.$key->member->id.'/show')}}">{{$key->member->first_name}} {{$key->member->middle_name}} {{$key->member->last_name}}</a>

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
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    <!-- /.box -->
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
@endsection
