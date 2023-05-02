<?php

/**
 * Created by PhpStorm.
 * User: Tj
 * Date: 6/29/2016
 * Time: 3:11 PM
 */

namespace App\Helpers;

use App\Models\Asset;
use App\Models\AssetValuation;
use App\Models\AuditTrail;
use App\Models\Contribution;
use App\Models\EventPayment;
use App\Models\Expense;
use App\Models\MemberTag;
use App\Models\OtherIncome;
use App\Models\Payroll;
use App\Models\PayrollMeta;
use App\Models\Pension;
use App\Models\PensionMeta;
use App\Models\Pledge;
use App\Models\PledgePayment;

use App\Models\Setting;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class GeneralHelper
{
    /*
     * determine interest
     */
    public static function send_sms($to, $msg)
    {
        if (Setting::where('setting_key', 'sms_enabled')->first()->setting_value == 1) {
            if (!empty(SmsGateway::find(Setting::where('setting_key',
                'active_sms')->first()->setting_value))
            ) {
                $active_sms = SmsGateway::find(Setting::where('setting_key',
                    'active_sms')->first()->setting_value);
                $append = "&";
                $append .= $active_sms->to_name . "=" . $to;
                $append .= "&" . $active_sms->msg_name . "=" . urlencode($msg);
                $url = $active_sms->url . $append;
                //send sms here
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
            }
        }

    }

    public static function pledge_amount_due($id)
    {
        $pledge_amount = Pledge::find($id)->amount;
        $payments = PledgePayment::where('pledge_id', $id)->sum('amount');
        return $pledge_amount - $payments;
    }

    public static function batch_total_amount($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            return Contribution::where('contribution_batch_id', $id)->sum('amount');
        } else {
            return Contribution::where('contribution_batch_id', $id)->whereBetween('date',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function total_contributions($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            return Contribution::sum('amount');
        } else {
            return Contribution::whereBetween('date',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function fund_total_amount($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            return Contribution::where('fund_id', $id)->sum('amount');
        } else {
            return Contribution::where('fund_id', $id)->whereBetween('date',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function campaign_total_amount($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            $amount = 0;
            foreach (Pledge::where('campaign_id', $id)->get() as $key) {
                $amount = $amount + PledgePayment::where('pledge_id', $key->id)->sum('amount');
            }
            return $amount;
        } else {

            $amount = 0;
            foreach (Pledge::where('campaign_id', $id)->whereBetween('created_at',
                [$start_date, $end_date])->get() as $key) {
                $amount = $amount + PledgePayment::where('pledge_id', $key->id)->sum('amount');
            }
            return $amount;

        }
    }

    public static function campaign_pledged_amount($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {

            return Pledge::where('campaign_id', $id)->sum('amount');
        } else {

            return Pledge::where('campaign_id', $id)->whereBetween('created_at',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function total_pledges($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {

            return Pledge::sum('amount');
        } else {

            return Pledge::whereBetween('created_at',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function total_pledges_payments($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {

            return PledgePayment::sum('amount');
        } else {

            return PledgePayment::whereBetween('created_at',
                [$start_date, $end_date])->sum('amount');

        }
    }


    public static function time_ago($eventTime)
    {
        $totaldelay = time() - strtotime($eventTime);
        if ($totaldelay <= 0) {
            return '';
        } else {
            if ($days = floor($totaldelay / 86400)) {
                $totaldelay = $totaldelay % 86400;
                return $days . ' days ago';
            }
            if ($hours = floor($totaldelay / 3600)) {
                $totaldelay = $totaldelay % 3600;
                return $hours . ' hours ago';
            }
            if ($minutes = floor($totaldelay / 60)) {
                $totaldelay = $totaldelay % 60;
                return $minutes . ' minutes ago';
            }
            if ($seconds = floor($totaldelay / 1)) {
                $totaldelay = $totaldelay % 1;
                return $seconds . ' seconds ago';
            }
        }
    }

    public static function member_total_contributions($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {

            return Contribution::where('member_id', $id)->sum('amount');
        } else {

            return Contribution::where('member_id', $id)->whereBetween('date',
                [$start_date, $end_date])->sum('amount');

        }
    }


    public static function member_total_pledges($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {

            return Pledge::where('member_id', $id)->sum('amount');
        } else {

            return Pledge::where('member_id', $id)->whereBetween('date',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function member_total_pledges_payments($id, $start_date = '', $end_date = '')
    {
        if (empty($start_date)) {

            return PledgePayment::where('member_id', $id)->sum('amount');
        } else {

            return PledgePayment::where('member_id', $id)->whereBetween('date',
                [$start_date, $end_date])->sum('amount');

        }
    }

    public static function single_payroll_total_pay($id)
    {
        return PayrollMeta::where('payroll_id', $id)->where('position', 'bottom_left')->sum('value');
    }

    public static function single_payroll_total_deductions($id)
    {
        return PayrollMeta::where('payroll_id', $id)->where('position', 'bottom_right')->sum('value');
    }

    public static function total_expenses($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            return Expense::sum('amount');
        } else {
            return Expense::whereBetween('date', [$start_date, $end_date])->sum('amount');

        }

    }

    public static function total_payroll($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            $payroll = 0;
            foreach (Payroll::all() as $key) {
                $payroll = $payroll + GeneralHelper::single_payroll_total_pay($key->id);
            }
            return $payroll;
        } else {
            $payroll = 0;
            foreach (Payroll::whereBetween('date', [$start_date, $end_date])->get() as $key) {
                $payroll = $payroll + GeneralHelper::single_payroll_total_pay($key->id);
            }
            return $payroll;

        }

    }


    public static function total_other_income($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            return OtherIncome::sum('amount');
        } else {
            return OtherIncome::whereBetween('date', [$start_date, $end_date])->sum('amount');

        }

    }

    public static function total_event_payments($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            return EventPayment::sum('amount');
        } else {
            return EventPayment::whereBetween('date', [$start_date, $end_date])->sum('amount');

        }

    }


    public static function audit_trail($notes)
    {
        $audit_trail = new AuditTrail();
        $audit_trail->user_id = Sentinel::getUser()->id;
        $audit_trail->user = Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name;
        $audit_trail->notes = $notes;
        $audit_trail->save();

    }


    public static function asset_valuation($id, $start_date = '')
    {

        if (empty($start_date)) {
            $value = 0;
            if (!empty(AssetValuation::where('asset_id', $id)->orderBy('date', 'desc')->first())) {
                $value = AssetValuation::where('asset_id', $id)->orderBy('date', 'desc')->first()->amount;
            }
            return $value;
        } else {
            $value = 0;
            if (!empty(AssetValuation::where('asset_id', $id)->where('date', '<=', $start_date)->orderBy('date',
                'desc')->first())
            ) {
                $value = AssetValuation::where('asset_id', $id)->where('date', '<=', $start_date)->orderBy('date',
                    'desc')->first()->amount;
            }
            return $value;

        }

    }

    public static function asset_type_valuation($id, $start_date = '')
    {

        if (empty($start_date)) {
            $value = 0;
            foreach (Asset::where('asset_type_id', $id)->get() as $key) {
                if (!empty(AssetValuation::where('asset_id', $key->id)->orderBy('date', 'desc')->first())) {
                    $value = AssetValuation::where('asset_id', $key->id)->orderBy('date', 'desc')->first()->amount;
                }
            }
            return $value;
        } else {
            $value = 0;
            foreach (Asset::where('asset_type_id', $id)->get() as $key) {
                if (!empty(AssetValuation::where('asset_id', $key->id)->where('date', '<=',
                    $start_date)->orderBy('date',
                    'desc')->first())
                ) {
                    $value = AssetValuation::where('asset_id', $key->id)->where('date', '<=',
                        $start_date)->orderBy('date',
                        'desc')->first()->amount;
                }
            }
            return $value;

        }

    }

    public static function createTreeView($parent, $menu, $selected = '')
    {
        if (empty($selected)) {
            $html = "";
            if (isset($menu['parents'][$parent])) {
                $html .= '<ul>';
                foreach ($menu['parents'][$parent] as $itemId) {
                    if (!isset($menu['parents'][$itemId])) {
                        $html .= '<li id="' . $itemId . '">' . $menu['items'][$itemId]['name'] . ' (' . MemberTag::where('tag_id',
                                $itemId)->count() . ' ' . trans_choice('general.people', 2) . ')</li>';
                    }
                    if (isset($menu['parents'][$itemId])) {
                        $html .= '
             <li id="' . $itemId . '">' . $menu['items'][$itemId]['name'] . " (" . MemberTag::where('tag_id',
                                $itemId)->count() . " " . trans_choice('general.people', 2) . ")";
                        $html .= GeneralHelper::createTreeView($itemId, $menu);
                        $html .= "</li>";
                    }
                }
                $html .= "</ul>";
            }
            return $html;
        } else {
            $html = "";
            if (isset($menu['parents'][$parent])) {
                $html .= '<ul>';
                foreach ($menu['parents'][$parent] as $itemId) {

                    if (!isset($menu['parents'][$itemId])) {
                        if (in_array($itemId, $selected)) {
                            $ext = "data-jstree='{\"selected\":true}'";
                        } else {
                            $ext = '';
                        }
                        $html .= '<li id="' . $itemId . '" ' . $ext . ' ' . $menu['items'][$itemId]['name'] . '</li>';
                    }
                    if (isset($menu['parents'][$itemId])) {
                        if (in_array($itemId, $selected)) {
                            $ext = "data-jstree='{\"selected\":true,\"open\":true}'";
                        } else {
                            $ext = '';
                        }
                        $html .= '
             <li id="' . $itemId . '"  ' . $ext . ' >' . $menu['items'][$itemId]['name'];
                        $html .= GeneralHelper::createTreeView($itemId, $menu, $selected);
                        $html .= "</li>";
                    }
                }
                $html .= "</ul>";
            }
            return $html;
        }
    }

    public static function single_pension_total_pay($id)
    {
        return PensionMeta::where('pension_id', $id)->where('position', 'bottom_left')->sum('value');
    }

    public static function single_pension_total_deductions($id)
    {
        return PensionMeta::where('pension_id', $id)->where('position', 'bottom_right')->sum('value');
    }
    public static function total_pension($start_date = '', $end_date = '')
    {
        if (empty($start_date)) {
            $pension = 0;
            foreach (Pension::all() as $key) {
                $pension = $pension + GeneralHelper::single_pension_total_pay($key->id);
            }
            return $pension;
        } else {
            $pension = 0;
            foreach (Pension::whereBetween('date', [$start_date, $end_date])->get() as $key) {
                $pension = $pension + GeneralHelper::single_pension_total_pay($key->id);
            }
            return $pension;

        }

    }
	
	
	
}