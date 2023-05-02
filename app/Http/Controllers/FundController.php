<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\BulkSms;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Fund;
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

class FundController extends Controller
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
        if (!Sentinel::hasAccess('contributions.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $data = Fund::all();

        return view('fund.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('contributions.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        //get custom fields
        return view('fund.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('contributions.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $fund = new Fund();
        $fund->user_id = Sentinel::getUser()->id;
        $fund->name = $request->name;
        $fund->notes = $request->notes;
        $fund->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/fund/data');
    }


    public function show($fund)
    {

        return view('fund.show', compact('fund'));
    }


    public function edit($fund)
    {
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        return view('fund.edit', compact('fund'));
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
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $fund = Fund::find($id);
        $fund->name = $request->name;
        $fund->notes = $request->notes;
        $fund->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/fund/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('contributions.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        Fund::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('contribution/fund/data');
    }

}
