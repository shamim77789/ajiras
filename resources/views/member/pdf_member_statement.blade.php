<style>

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        display: table;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-justify {
        text-align: justify;
    }

    .pull-right {
        float: right !important;
    }
</style>


<div>
    <h3 class="text-center"><b>{{\App\Models\Setting::where('setting_key','company_name')->first()->setting_value}}</b>
    </h3>
    <h3 class="text-center"><b>{{trans_choice('general.member',1)}} {{trans_choice('general.statement',1)}}</b></h3>

    <div style="width: 100%;margin-left: auto;font-size:10px;margin-right: auto;border-top: solid thin #2cc3dd;border-bottom: solid thin #2cc3dd;padding-top: 40px;text-transform: capitalize">
        <table style="margin-top: 20px">
            <tr>
                <td style="width: 30%;margin-right: 20px;float: left">
                    <b>{{$member->first_name}} {{$member->middle_name}} {{$member->last_name}}</b><br><br>
                    <b>{{trans_choice('general.date',1)}}:</b>{{date("Y-m-d")}}<br><br>
                </td>
                <td style="width: 60%;margin-right: 20px;float: left">
                    <table width="100%">
                        <tr>
                            <td> <b>{{trans_choice('general.total',1)}} {{trans_choice('general.contribution',2)}} </b></td>
                            <td>{{number_format(\App\Helpers\GeneralHelper::member_total_contributions($member->id),2)}}</td>
                        </tr>
                        <tr>
                            <td> <b>{{trans_choice('general.total',2)}} {{trans_choice('general.pledge',2)}}</b></td>
                            <td>{{number_format(\App\Helpers\GeneralHelper::total_pledges_payments($member->id),2)}}</td>
                        </tr>
                        <tr>
                            <td><b>{{trans_choice('general.total',1)}}</b></td>
                            <td>{{number_format(\App\Helpers\GeneralHelper::member_total_contributions($member->id)+\App\Helpers\GeneralHelper::total_pledges_payments($member->id),2)}}</td>
                        </tr>

                    </table>
                </td>

            </tr>
        </table>
    </div>
    <div style="margin-top:30px;margin-left: auto;margin-right: auto;text-transform: capitalize;font-size: 8px;">
        <h3 class="text-center"><b>{{trans_choice('general.contribution',2)}}</b></h3>
        @if(count($member->contributions)>0)
            <table border="1">
                <tr>
                    <th>{{trans_choice('general.batch',1)}}</th>
                    <th>{{trans_choice('general.amount',1)}}</th>
                    <th>{{trans_choice('general.method',1)}}</th>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.note',2)}}</th>
                </tr>
                <tbody>


                @foreach($member->contributions as $key)
                    <tr>
                        <td>
                            @if(!empty($key->batch))
                                {{$key->batch->id}}
                                @if(!empty($key->batch->name))
                                    - {{$key->batch->name}}
                                @endif
                            @endif
                        </td>
                        <td>
                            @if(\App\Models\Setting::where('setting_key', 'currency_position')->first()->setting_value=='left')
                                {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value }}
                                {{number_format($key->amount,2)}}
                            @else
                                {{number_format($key->amount,2)}}  {{ \App\Models\Setting::where('setting_key', 'currency_symbol')->first()->setting_value}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($key->payment_method))
                                {{$key->payment_method->name}}
                            @endif
                        </td>
                        <td>{{ $key->date }}</td>

                        <td>{!!   $key->notes !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <h5>No {{trans_choice('general.contribution',2)}} made</h5>
        @endif
    </div>
    <div style="margin-top:30px;margin-left: auto;margin-right: auto;text-transform: capitalize;font-size: 8px;">
        <h3 class="text-center"><b>{{trans_choice('general.pledge',2)}}</b></h3>
        @if(count($member->pledges)>0)
            <table border="1">
                <tr>
                    <th>{{trans_choice('general.campaign',1)}}</th>
                    <th>{{trans_choice('general.amount',1)}}</th>
                    <th>{{trans_choice('general.date',1)}}</th>
                    <th>{{trans_choice('general.note',2)}}</th>
                </tr>
                <tbody>
                @foreach($member->pledges as $key)
                    <tr>
                        <td>
                            @if(!empty($key->campaign))
                                {{$key->campaign->id}}
                                @if(!empty($key->campaign->name))
                                    - {{$key->campaign->name}}
                                @endif
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
                        </td>

                        <td>{{ $key->date }}</td>
                        <td>{!!   $key->notes !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <h5>No {{trans_choice('general.pledge',2)}} made</h5>
        @endif
    </div>
</div>
