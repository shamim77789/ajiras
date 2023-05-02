<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\BulkSms;
use App\Helpers\GeneralHelper;
use App\Models\Borrower;
use App\Models\Dioces;
use App\Models\Branch;
use App\Models\ContributionBatch;
use App\Models\Fund;
use App\Models\State;
use App\Models\Collateral;
use App\Models\CollateralType;
use App\Models\UserStates;
use App\Models\Contribution;
use App\Models\ContributionType;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\EventPayment;
use App\Models\GroupType;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanSchedule;
use App\Models\OtherIncome;
use App\Models\Payroll;
use App\Models\Pledge;
use App\Models\PledgePayment;
use App\Models\SavingTransaction;
use App\Models\ContributionTypeEstimation;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Api\ClickatellHttp;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Session;
use Auth;
class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cash_flow(Request $request)
    {
        if (!Sentinel::hasAccess('reports')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_id = $request->branch_id;
        $expenses = Expense::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('amount');
        $payroll = Payroll::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('paid_amount');
        $contributions = Contribution::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('amount');
        $other_income = OtherIncome::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('amount');
        $events = EventPayment::leftJoin('events', 'events.id', 'event_payments.event_id')
            ->when($start_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('event_payments.date', [$start_date, $end_date]);
            })->when($branch_id, function ($query) use ($branch_id) {
                $query->where('events.branch_id', $branch_id);
            })->sum('event_payments.amount');
        $pledges = PledgePayment::leftJoin('pledges', 'pledges.id', 'pledge_payments.pledge_id')
            ->when($start_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('pledge_payments.date', [$start_date, $end_date]);
            })->when($branch_id, function ($query) use ($branch_id) {
                $query->where('pledges.branch_id', $branch_id);
            })->sum('pledge_payments.amount');
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        $total_payments = $expenses + $payroll;
        $total_receipts = $pledges + $contributions + $other_income + $events;
        $cash_balance = $total_receipts - $total_payments;
        return view('report.cash_flow',
            compact('expenses', 'payroll', 'contributions', 'total_payments', 'other_income', 'pledges',
                'total_receipts', 'cash_balance', 'start_date',
                'end_date', 'events', 'branch_id', 'branches'));
    }

    public function profit_loss(Request $request)
    {
        if (!Sentinel::hasAccess('reports')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_id = $request->branch_id;
        $expenses = Expense::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('amount');
        $payroll = Payroll::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('paid_amount');
        $contributions = Contribution::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('amount');
        $other_income = OtherIncome::when($start_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('date', [$start_date, $end_date]);
        })->when($branch_id, function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })->sum('amount');
        $events = EventPayment::leftJoin('events', 'events.id', 'event_payments.event_id')
            ->when($start_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('event_payments.date', [$start_date, $end_date]);
            })->when($branch_id, function ($query) use ($branch_id) {
                $query->where('events.branch_id', $branch_id);
            })->sum('event_payments.amount');
        $pledges = PledgePayment::leftJoin('pledges', 'pledges.id', 'pledge_payments.pledge_id')
            ->when($start_date, function ($query) use ($start_date, $end_date) {
                $query->whereBetween('pledge_payments.date', [$start_date, $end_date]);
            })->when($branch_id, function ($query) use ($branch_id) {
                $query->where('pledges.branch_id', $branch_id);
            })->sum('pledge_payments.amount');
        $operating_expenses = $expenses + $payroll;
        $operating_profit = $contributions + $pledges + $other_income + $events;
        $gross_profit = $operating_profit - $operating_expenses;
        $net_profit = $gross_profit;
        //build graphs here
        $monthly_net_income_data = array();
        $monthly_operating_profit_expenses_data = array();
        $monthly_overview_data = array();
        if (isset($request->end_date)) {
            $date = $request->end_date;
        } else {
            $date = date("Y-m-d");
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        $start_date1 = date_format(date_sub(date_create($date),
            date_interval_create_from_date_string('1 years')),
            'Y-m-d');
        $start_date2 = date_format(date_sub(date_create($date),
            date_interval_create_from_date_string('1 years')),
            'Y-m-d');
        $start_date3 = date_format(date_sub(date_create($date),
            date_interval_create_from_date_string('1 years')),
            'Y-m-d');
        for ($i = 1; $i < 14; $i++) {
            $d = explode('-', $start_date1);
            $o_profit = Contribution::where('year', $d[0])->where('month',
                    $d[1])->sum('amount') + OtherIncome::where('year', $d[0])->where('month',
                    $d[1])->sum('amount') + PledgePayment::where('year', $d[0])->where('month', $d[1])->sum('amount') + EventPayment::where('year', $d[0])->where('month',
                    $d[1])->sum('amount');
            $o_expense = Expense::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            foreach (Payroll::where('year', $d[0])->where('month',
                $d[1])->get() as $key) {
                $o_expense = $o_expense + GeneralHelper::single_payroll_total_pay($key->id);
            }

            $ext = ' ' . $d[0];
            $n_income = $o_profit - $o_expense;
            array_push($monthly_net_income_data, array(
                'month' => date_format(date_create($start_date1),
                    'M' . $ext),
                'amount' => $n_income

            ));
            //add 1 month to start date
            $start_date1 = date_format(date_add(date_create($start_date1),
                date_interval_create_from_date_string('1 months')),
                'Y-m-d');
        }
        for ($i = 1; $i < 14; $i++) {
            $d = explode('-', $start_date2);
            //get loans in that period
            $o_profit = Contribution::where('year', $d[0])->where('month',
                    $d[1])->sum('amount') + OtherIncome::where('year', $d[0])->where('month',
                    $d[1])->sum('amount') + PledgePayment::where('year', $d[0])->where('month', $d[1])->sum('amount') + EventPayment::where('year', $d[0])->where('month',
                    $d[1])->sum('amount');
            $o_expense = Expense::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            foreach (Payroll::where('year', $d[0])->where('month',
                $d[1])->get() as $key) {
                $o_expense = $o_expense + GeneralHelper::single_payroll_total_pay($key->id);
            }

            $ext = ' ' . $d[0];
            array_push($monthly_operating_profit_expenses_data, array(
                'month' => date_format(date_create($start_date2),
                    'M' . $ext),
                'profit' => $o_profit,
                'expenses' => $o_expense

            ));
            //add 1 month to start date
            $start_date2 = date_format(date_add(date_create($start_date2),
                date_interval_create_from_date_string('1 months')),
                'Y-m-d');
        }
        for ($i = 1; $i < 14; $i++) {
            $d = explode('-', $start_date3);
            //get loans in that period
            $contributions = Contribution::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            $pledges = PledgePayment::where('year', $d[0])->where('month', $d[1])->sum('amount');
            $other_income = OtherIncome::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            $events = EventPayment::where('year', $d[0])->where('month',
                $d[1])->sum('amount');

            $ext = ' ' . $d[0];
            array_push($monthly_overview_data, array(
                'month' => date_format(date_create($start_date3),
                    'M' . $ext),
                'contributions' => $contributions,
                'pledges' => $pledges,
                'other_income' => $other_income,
                'events' => $events
            ));
            //add 1 month to start date
            $start_date3 = date_format(date_add(date_create($start_date3),
                date_interval_create_from_date_string('1 months')),
                'Y-m-d');
        }
        $monthly_net_income_data = json_encode($monthly_net_income_data);
        $monthly_operating_profit_expenses_data = json_encode($monthly_operating_profit_expenses_data);
        $monthly_overview_data = json_encode($monthly_overview_data);
        return view('report.profit_loss',
            compact('expenses', 'payroll', 'operating_expenses', 'other_income',
                'contributions', 'pledges', 'operating_profit', 'gross_profit', 'start_date',
                'end_date', 'net_profit', 'monthly_net_income_data',
                'monthly_operating_profit_expenses_data', 'monthly_overview_data', 'events', 'branch_id', 'branches'));
    }


	public function contribution_estimate(Request $request)
	{
		$user_id = Sentinel::getUser()->id;
		$dioces_id = Sentinel::getUser()->dioces;
			
		if (!Sentinel::hasAccess('reports')) 
		{
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		if($dioces_id == '0')
			$dioces = Dioces::get();
		else
			$dioces = Dioces::where('id', $dioces_id)->get();
		
		$funds = array();
		foreach(Fund::get() as $key)
		{
            $funds[$key->id] = $key->name;
		}

		$states = array();		

		return view('report.contribution_estimate',
            compact('funds','state','start_date','end_date','dioces'));
	}


	public function expense_estimate(Request $request)
	{
        if (!Sentinel::hasAccess('reports')) 
		{
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$user_id = Sentinel::getUser()->id;
		$dioces_id = Sentinel::getUser()->dioces;

		if($dioces_id == '0')
			$dioces = Dioces::get();
		else
			$dioces = Dioces::where('id', $dioces_id)->get();

		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		
		$funds = array();
		foreach(Fund::get() as $key)
		{
            $funds[$key->id] = $key->name;
		}

		$states = array();
		foreach (State::get() as $key) 
		{
            $states[$key->id] = $key->name;
        }
		$branches = array();
        foreach (Branch::get() as $key) 
		{
            $branches[$key->id] = $key->name;
        }

		return view('report.expense_estimate',
            compact('branches','dioces','funds','states','start_date','end_date'));
	}

	public function contribution_report(Request $request)
	{		
		if (!Sentinel::hasAccess('reports')) 
		{
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$type = array();
		$con_type = array();
		
		if(!empty($request->input()))
		{
			$start_date = date($request->start_date);
			$end_date = date($request->end_date);
			$fund_type = $request->fund_type;
			$batches = $request->batches;
	
			$contribution = Contribution::where('contribution_batch_id', $batches)->where('fund_id', $fund_type)
							->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 00:00:00"])
							->get();

			if(!empty($contribution))
			{
				foreach($contribution as $key => $value)
				{
					$contribution_type = ContributionType::where('id',$value->contribution_type_id)->first();
					if(!empty($contribution_type))
					{
						$con_type[$key]['name'] = $contribution_type->name;
						$con_type[$key]['amount'] = ContributionTypeEstimation::where('contribution_type_id', $value->contribution_type_id)
												  ->sum('estimation_amount');
					}
				}
			}
			
		}
		
		
		$user_id = Sentinel::getUser()->id;
		if(!empty($request->input()))
		{
			$contribution_types = ContributionType::where('fund', $request->fund)->get();
			if(!empty($contribution_types))
			{
				foreach($contribution_types as $key => $contribution_type)
				{
					$type[$key]['name'] = $contribution_type->name;
//					$type[$key]['amount'] = 
				}
			}
		}
		
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$funds = array();
		foreach(Fund::where('user_id', $user_id)->get() as $key)
		{
            $funds[$key->id] = $key->name;
		}

		$batches = array();
		foreach(ContributionBatch::where('user_id', $user_id)->get() as $key)
		{
            $batches[$key->id] = $key->name;
		}

		return view('report.contribution_report',
            compact('funds','batches','start_date','end_date','con_type'));
	}		

	public function contribution_summary(Request $request)
	{
        if (!Sentinel::hasAccess('reports')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$funds = array();
		foreach(Fund::get() as $key)
		{
            $funds[$key->id] = $key->name;
		}

		$batches = array();
		foreach(ContributionBatch::get() as $key)
		{
            $batches[$key->id] = $key->name;
		}

		return view('report.contribution_summary',
            compact('funds','batches','start_date','end_date'));
	}		
	
	public function contribution_batch(Request $request)
	{
        if (!Sentinel::hasAccess('reports')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$funds = array();
		foreach(Fund::get() as $key)
		{
            $funds[$key->id] = $key->name;
		}

		$batches = array();
		foreach(ContributionBatch::get() as $key)
		{
            $batches[$key->id] = $key->name;
		}

		return view('report.contribution_batch',
            compact('funds','batches','start_date','end_date'));
	}					

	
	public function propotional_contribution(Request $request)
	{
        if (!Sentinel::hasAccess('reports')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$funds = array();
		foreach(Fund::get() as $key)
		{
            $funds[$key->id] = $key->name;
		}

		$batches = array();
		foreach(ContributionBatch::get() as $key)
		{
            $batches[$key->id] = $key->name;
		}

		return view('report.propotional_contribution',
            compact('funds','batches','start_date','end_date'));
	}
	public function contribution_type(Request $request)
	{
        if (!Sentinel::hasAccess('reports')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$group_type = array();
		foreach(GroupType::get() as $key)
		{
            $group_type[$key->id] = $key->group_type;
		}
		return view('report.contribution_type',
            compact('group_type','start_date','end_date'));
	}									
	
}
