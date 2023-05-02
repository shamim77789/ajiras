<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;

use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Pledge;
use App\Models\PledgePayment;
use App\Models\Fund;
use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class PledgePaymentController extends Controller
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
        if (!Sentinel::hasAccess('pledges.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        return view('pledge_payment.data', compact('data'));
    }

    public function get_pledge_payments(Request $request)
    {
        if (!Sentinel::hasAccess('pledges')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        if (!empty($request->member_id)) {
            $member_id = $request->member_id;
        } else {
            $member_id = null;
        }
        if (!empty($request->pledge_id)) {
            $pledge_id = $request->pledge_id;
        } else {
            $pledge_id = null;
        }
        if (!empty($request->family_id)) {
            $family_id = $request->family_id;
        } else {
            $family_id = null;
        }
        return DataTables::of(DB::table("pledge_payments")->leftJoin('members', 'members.id', 'pledge_payments.member_id')->leftJoin('payment_methods', 'payment_methods.id', 'pledge_payments.payment_method_id')->selectRaw("pledge_payments.*,members.first_name member_first_name,members.last_name member_last_name,members.middle_name member_middle_name,payment_methods.name payment_method")->when($member_id, function ($query) use ($member_id) {
            $query->where("pledge_payments.member_id", $member_id);
        })->when($pledge_id, function ($query) use ($pledge_id) {
            $query->where("pledge_payments.pledge_id", $pledge_id);
        })->when($family_id, function ($query) use ($family_id) {
            $query->where("pledge_payments.family_id", $family_id);
        }))->editColumn('member', function ($data) {
            return '<a href="' . url('member/' . $data->id . '/show') . '" class="">' . $data->member_first_name . ' ' . $data->member_middle_name . ' ' . $data->member_last_name . '</a>';
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('pledges.view')) {

            }
            if (Sentinel::hasAccess('pledges.update')) {
                $action .= '<li><a href="' . url('pledge/payment/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('pledges.delete')) {
                $action .= '<li><a href="' . url('pledge/payment/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return $data->id;

        })->editColumn('amount', function ($data) {

            return number_format($data->amount);

        })->rawColumns(['id', 'member', 'action', 'campaign', 'amount'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if (!Sentinel::hasAccess('pledges.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $payment_methods = array();
        foreach (PaymentMethod::all() as $key) {
            $payment_methods[$key->id] = $key->name;
        }
        $members = array();
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
        }
        //get custom fields
        return view('pledge_payment.create', compact('id', 'payment_methods', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (!Sentinel::hasAccess('pledges.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $pledge = Pledge::find($id);
        $payment = new PledgePayment();
        $payment->member_id = $pledge->member_id;
        $payment->pledge_id = $id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->amount = $request->amount;
        $payment->notes = $request->notes;
        $payment->date = $request->date;
        $date = explode('-', $request->date);
        $payment->year = $date[0];
        $payment->month = $date[1];
        $payment->save();
        GeneralHelper::audit_trail("Added pledge payment with id:" . $payment->id);
        Flash::success(trans('general.successfully_saved'));
        if (isset($request->return_url)) {
            return redirect($request->return_url);
        }
        return redirect('pledge/data');
    }


    public function show($pledge_payment)
    {
        if (!Sentinel::hasAccess('pledges.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $users = User::all();
        $user = array();
        foreach ($users as $key) {
            $user[$key->id] = $key->first_name . ' ' . $key->last_name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'contributions')->get();
        return view('pledge_payment.show', compact('pledge_payment', 'user', 'custom_fields'));
    }


    public function edit($pledge_payment)
    {
        if (!Sentinel::hasAccess('pledges.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $payment_methods = array();
        foreach (PaymentMethod::all() as $key) {
            $payment_methods[$key->id] = $key->name;
        }
        //get custom fields
        return view('pledge_payment.edit',
            compact('pledge_payment', 'payment_methods'));
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
        if (!Sentinel::hasAccess('pledges.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $payment = PledgePayment::find($id);
        $payment->payment_method_id = $request->payment_method_id;
        $payment->amount = $request->amount;
        $payment->notes = $request->notes;
        $payment->date = $request->date;
        $date = explode('-', $request->date);
        $payment->year = $date[0];
        $payment->month = $date[1];
        $payment->save();
        GeneralHelper::audit_trail("Updated pledge payment with id:" . $payment->id);
        Flash::success(trans('general.successfully_saved'));
        if (isset($request->return_url)) {
            return redirect($request->return_url);
        }
        return redirect('pledge/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        if (!Sentinel::hasAccess('pledges.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        PledgePayment::destroy($id);
        GeneralHelper::audit_trail("Deleted pledge payment with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        if (isset($request->return_url)) {
            return redirect($request->return_url);
        }
        return redirect()->back();
    }


}
