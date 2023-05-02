<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\BulkSms;
use App\Models\Contribution;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\ContributionBatch;
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
use DB;
use App\Models\ContributionType;
use App\Models\Campaign;
use App\Models\PledgeType;
use App\Models\OtherIncome;
use App\Models\OtherIncomeType;
use App\Models\Fund;
use App\Models\FundTransfer;

class FundTransferController extends Controller
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
    public function index()
    {
        if (!Sentinel::hasAccess('fund_transfer')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $fund_transfer = DB::table('fund_transfer as ft')->get();
        return view('fund_transfer.data', compact('fund_transfer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('fund_transfer.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
			
		$campaign_type = PledgeType::select('id','name')->get();
		$campaign = Campaign::select('id','name')->get();		
        $otherIncome = OtherIncomeType::get();
        $fund = Fund::get();
        $ContributionType = ContributionType::all();

		//get custom fields
        return view('fund_transfer.create')->with(compact('campaign_type','campaign','contribution_type','ContributionType','fund','otherIncome'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
        if (!Sentinel::hasAccess('fund_transfer.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$fund_transfer = new FundTransfer();
		$fund_transfer->fund_name = $request->fund_name;
		$fund_transfer->fund_date = $request->fund_date;
		$fund_transfer->from = $request->from;
		$fund_transfer->to = $request->to;
		$fund_transfer->amount = $request->amount;
		$fund_transfer->notes = $request->notes;
		$fund_transfer->created_at = date('y-m-d H:i:s');
		$fund_transfer->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('fund_transfer/data');
    }


    public function show($contribution_batch)
    {
    }


    public function edit($id)
    {
        if (!Sentinel::hasAccess('fund_transfer.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$campaign_type = PledgeType::select('id','name')->get();
		$campaign = Campaign::select('id','name')->get();		
        $otherIncome = OtherIncomeType::get();
        $fund = Fund::get();
		$fund_transfer = FundTransfer::find($id);
		
        $ContributionType = ContributionType::all();
		return view('fund_transfer.edit', compact('campaign_type','campaign','contribution_type','ContributionType','fund','otherIncome','fund_transfer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Sentinel::hasAccess('fund_transfer.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		$fund_transfer = FundTransfer::find($id);
		$fund_transfer->fund_name = $request->fund_name;
		$fund_transfer->fund_date = $request->fund_date;
		$fund_transfer->from = $request->from;
		$fund_transfer->to = $request->to;
		$fund_transfer->amount = $request->amount;
		$fund_transfer->notes = $request->notes;
		$fund_transfer->updated_at = date('y-m-d H:i:s');
		$fund_transfer->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('fund_transfer/data');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('fund_transfer.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		FundTransfer::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('fund_transfer/data');
    }

}
