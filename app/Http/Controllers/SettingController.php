<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Setting;
use App\Models\SmsGateway;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class SettingController extends Controller
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
    public function updateSystem()
    {
        Artisan::call('migrate');
        Flash::success("Successfully Updated");
        return redirect('setting/data');
    }

    public function index()
    {
        if (!Sentinel::hasAccess('settings')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $sms_gateways = array();
        foreach (SmsGateway::all() as $key) {
            $sms_gateways[$key->id] = $key->name;
        }
        return view('setting.data',compact('sms_gateways'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!Sentinel::hasAccess('settings')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        Setting::where('setting_key', 'company_name')->update(['setting_value' => $request->company_name]);
        Setting::where('setting_key', 'company_phone')->update(['setting_value' => $request->company_phone]);
        Setting::where('setting_key', 'company_email')->update(['setting_value' => $request->company_email]);
        Setting::where('setting_key', 'company_website')->update(['setting_value' => $request->company_website]);
        Setting::where('setting_key', 'portal_address')->update(['setting_value' => $request->portal_address]);
        Setting::where('setting_key', 'company_address')->update(['setting_value' => $request->company_address]);
        Setting::where('setting_key', 'currency_symbol')->update(['setting_value' => $request->currency_symbol]);
        Setting::where('setting_key', 'currency_position')->update(['setting_value' => $request->currency_position]);
        Setting::where('setting_key', 'company_currency')->update(['setting_value' => $request->company_currency]);
        Setting::where('setting_key', 'company_country')->update(['setting_value' => $request->company_country]);
        Setting::where('setting_key', 'sms_enabled')->update(['setting_value' => $request->sms_enabled]);
        Setting::where('setting_key', 'active_sms')->update(['setting_value' => $request->active_sms]);

        Setting::where('setting_key',
            'password_reset_subject')->update(['setting_value' => $request->password_reset_subject]);
        Setting::where('setting_key',
            'password_reset_template')->update(['setting_value' => $request->password_reset_template]);
        Setting::where('setting_key',
            'follow_up_sms_template')->update(['setting_value' => $request->follow_up_sms_template]);
        Setting::where('setting_key',
            'follow_up_email_template')->update(['setting_value' => $request->follow_up_email_template]);
        Setting::where('setting_key',
            'follow_up_email_subject')->update(['setting_value' => $request->follow_up_email_subject]);
        Setting::where('setting_key',
            'payment_email_subject')->update(['setting_value' => $request->payment_email_subject]);
        Setting::where('setting_key',
            'payment_email_template')->update(['setting_value' => $request->payment_email_template]);
        Setting::where('setting_key',
            'payment_sms_template')->update(['setting_value' => $request->payment_sms_template]);
        Setting::where('setting_key',
            'auto_payment_receipt_sms')->update(['setting_value' => $request->auto_payment_receipt_sms]);
        Setting::where('setting_key',
            'auto_payment_receipt_email')->update(['setting_value' => $request->auto_payment_receipt_email]);
        Setting::where('setting_key',
            'google_maps_key')->update(['setting_value' => $request->google_maps_key]);
        Setting::where('setting_key', 'email_volunteer_assignment')->update(['setting_value' => $request->email_volunteer_assignment]);
        Setting::where('setting_key',
            'volunteer_assignment_email_subject')->update(['setting_value' => $request->volunteer_assignment_email_subject]);
        Setting::where('setting_key',
            'volunteer_assignment_email_template')->update(['setting_value' => $request->volunteer_assignment_email_template]);


        Setting::where('setting_key', 'enable_online_giving')->update(['setting_value' => $request->enable_online_giving]);
        Setting::where('setting_key', 'paypal_email')->update(['setting_value' => $request->paypal_email]);
        Setting::where('setting_key', 'paynow_id')->update(['setting_value' => $request->paynow_id]);
        Setting::where('setting_key', 'paynow_key')->update(['setting_value' => $request->paynow_key]);

        Setting::where('setting_key', 'stripe_secret_key')->update(['setting_value' => $request->stripe_secret_key]);
        Setting::where('setting_key', 'stripe_publishable_key')->update(['setting_value' => $request->stripe_publishable_key]);
        Setting::where('setting_key', 'enable_cron')->update(['setting_value' => $request->enable_cron]);

        if ($request->hasFile('company_logo')) {
            $file = array('company_logo' => $request->file('company_logo'));
            $rules = array('company_logo' => 'required|mimes:jpeg,jpg,bmp,png');
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                Setting::where('setting_key',
                    'company_logo')->update(['setting_value' => $request->file('company_logo')->getClientOriginalName()]);
                $request->file('company_logo')->move(public_path() . '/uploads',
                    $request->file('company_logo')->getClientOriginalName());
            }
        }

        GeneralHelper::audit_trail("Updated Settings");
        Flash::success("Successfully Saved");
        return redirect('setting/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
