<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\BulkSms;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\PaymentMethod;
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

class PaymentMethodController extends Controller
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
        $data = PaymentMethod::all();

        return view('payment_method.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        //get custom fields
        return view('payment_method.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payment_method = new PaymentMethod();
        $payment_method->name = $request->name;
        $payment_method->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/payment_method/data');
    }


    public function show($payment_method)
    {

        return view('payment_method.show', compact('payment_method'));
    }


    public function edit($payment_method)
    {
        return view('payment_method.edit', compact('payment_method'));
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
        $payment_method = PaymentMethod::find($id);
        $payment_method->name = $request->name;
        $payment_method->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/payment_method/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        PaymentMethod::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('contribution/payment_method/data');
    }

}
