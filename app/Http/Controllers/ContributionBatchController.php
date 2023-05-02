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

class ContributionBatchController extends Controller
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
        if (!Sentinel::hasAccess('contributions')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $data = ContributionBatch::all();

        return view('contribution_batch.data', compact('data'));
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
        return view('contribution_batch.create');
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
        $contribution_batch = new ContributionBatch();
        $contribution_batch->user_id = Sentinel::getUser()->id;
        $contribution_batch->name = $request->name;
        $contribution_batch->notes = $request->notes;
        $contribution_batch->date = $request->date;
        $contribution_batch->attendees = $request->attendees;
		$date = explode('-', $request->date);
        $contribution_batch->year = $date[0];
        $contribution_batch->month = $date[1];
        $contribution_batch->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/batch/data');
    }


    public function show($contribution_batch)
    {
        if (!Sentinel::hasAccess('contributions.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        return view('contribution_batch.show', compact('contribution_batch'));
    }


    public function edit($contribution_batch)
    {
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        return view('contribution_batch.edit', compact('contribution_batch'));
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
        $contribution_batch = ContributionBatch::find($id);
        $contribution_batch->name = $request->name;
        $contribution_batch->notes = $request->notes;
        $contribution_batch->date = $request->date;
        $contribution_batch->attendees = $request->attendees;
		$date = explode('-', $request->date);
        $contribution_batch->year = $date[0];
        $contribution_batch->month = $date[1];
        $contribution_batch->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/batch/data');
    }
    public function close( $id)
    {
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $contribution_batch = ContributionBatch::find($id);
        $contribution_batch->status = 1;
        $contribution_batch->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/batch/data');
    }
    public function open( $id)
    {
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $contribution_batch = ContributionBatch::find($id);
        $contribution_batch->status = 0;
        $contribution_batch->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/batch/data');
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
        ContributionBatch::destroy($id);
        Contribution::where('contribution_batch_id',$id)->delete();
        Flash::success(trans('general.successfully_deleted'));
        return redirect('contribution/batch/data');
    }

}
