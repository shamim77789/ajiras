<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;
use App\Helpers\Infobip;
use App\Helpers\RouteSms;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Email;
use App\Models\Event;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanSchedule;
use App\Models\Payroll;
use App\Models\PayrollMeta;
use App\Models\PayrollTemplateMeta;
use App\Models\Pledge;
use App\Models\Saving;
use App\Models\SavingProduct;
use App\Models\SavingTransaction;
use App\Models\Setting;
use App\Models\Sms;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Api\ClickatellHttp;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use PDF;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Http\Requests;

class CronController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Setting::where('setting_key', 'enable_cron')->first()->setting_value == 0) {
            //someone attempted to run con job but it is disabled
            Mail::raw('Someone attempted to run con job but it is disabled, please enable it in settings',
                function ($message) {
                    $message->from(Setting::where('setting_key', 'company_email')->first()->setting_value,
                        Setting::where('setting_key', 'company_name')->first()->setting_value);
                    $message->to(Setting::where('setting_key', 'company_email')->first()->setting_value);
                    $headers = $message->getHeaders();
                    $message->setContentType('text/html');
                    $message->setSubject('Cron Job Failed');

                });
            return 'cron job disabled';
        } else {

            //check for recurring expenses
            $expenses = Expense::where('recurring', 1)->get();
            foreach ($expenses as $expense) {
                if (empty($expense->recur_end_date)) {
                    if ($expense->recur_next_date == date("Y-m-d")) {
                        $exp1 = new Expense();
                        $exp1->expense_type_id = $expense->expense_type_id;
                        $exp1->amount = $expense->amount;
                        $exp1->notes = $expense->notes;
                        $exp1->date = date("Y-m-d");
                        $date = explode('-', date("Y-m-d"));
                        $exp1->year = $date[0];
                        $exp1->month = $date[1];
                        $exp1->save();
                        $custom_fields = CustomFieldMeta::where('parent_id', $expense->id)->where('category',
                            'expenses')->get();
                        foreach ($custom_fields as $key) {
                            $custom_field = new CustomFieldMeta();
                            $custom_field->name = $key->name;
                            $custom_field->parent_id = $exp1->id;
                            $custom_field->custom_field_id = $key->custom_field_id;
                            $custom_field->category = "expenses";
                            $custom_field->save();
                        }
                        $exp2 = Expense::find($expense->id);
                        $exp2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                            date_interval_create_from_date_string($expense->recur_frequency . ' ' . $expense->recur_type . 's')),
                            'Y-m-d');
                        $exp2->save();
                    }
                } else {
                    if (date("Y-m-d") <= $expense->recur_end_date) {
                        if ($expense->recur_next_date == date("Y-m-d")) {
                            $exp1 = new Expense();
                            $exp1->expense_type_id = $expense->expense_type_id;
                            $exp1->amount = $expense->amount;
                            $exp1->notes = $expense->notes;
                            $exp1->date = date("Y-m-d");
                            $date = explode('-', date("Y-m-d"));
                            $exp1->year = $date[0];
                            $exp1->month = $date[1];
                            $exp1->save();
                            $custom_fields = CustomFieldMeta::where('parent_id', $expense->id)->where('category',
                                'expenses')->get();
                            foreach ($custom_fields as $key) {
                                $custom_field = new CustomFieldMeta();
                                $custom_field->name = $key->name;
                                $custom_field->parent_id = $exp1->id;
                                $custom_field->custom_field_id = $key->custom_field_id;
                                $custom_field->category = "expenses";
                                $custom_field->save();
                            }
                            $exp2 = Expense::find($expense->id);
                            $exp2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                                date_interval_create_from_date_string($expense->recur_frequency . ' ' . $expense->recur_type . 's')),
                                'Y-m-d');
                            $exp2->save();
                        }
                    }
                }
            }
            //check for recurring events
            $events = Event::where('recurring', 1)->get();
            foreach ($events as $event) {
                if (empty($event->recur_end_date)) {
                    if ($event->recur_next_date == date("Y-m-d")) {
                        $exp1 = new Event();
                        $exp1->parent_id = $event->id;
                        $exp1->branch_id = $event->branch_id;
                        $exp1->user_id = $event->user_id;
                        $exp1->event_location_id = $event->event_location_id;
                        $exp1->event_calendar_id = $event->event_calendar_id;
                        $exp1->name = $event->name;
                        $exp1->cost = $event->cost;
                        $exp1->all_day = $event->all_day;
                        $exp1->start_date = $event->start_date;
                        $exp1->start_time = $event->start_time;
                        $exp1->end_date = $event->end_date;
                        $exp1->end_time = $event->end_time;
                        $exp1->checkin_type = $event->checkin_type;
                        $exp1->tags = $event->tags;
                        $exp1->include_checkout = $event->include_checkout;
                        $exp1->family_checkin = $event->family_checkin;
                        $exp1->featured_image = $event->featured_image;
                        $exp1->gallery = $event->gallery;
                        $exp1->files = $event->files;
                        $exp1->longitude = $event->longitude;
                        $exp1->latitude = $event->latitude;
                        $exp1->notes = $event->notes;
                        $date = explode('-', date("Y-m-d"));
                        $exp1->year = $date[0];
                        $exp1->month = $date[1];
                        $exp1->save();
                        $custom_fields = CustomFieldMeta::where('parent_id', $event->id)->where('category',
                            'events')->get();
                        foreach ($custom_fields as $key) {
                            $custom_field = new CustomFieldMeta();
                            $custom_field->name = $key->name;
                            $custom_field->parent_id = $exp1->id;
                            $custom_field->custom_field_id = $key->custom_field_id;
                            $custom_field->category = "events";
                            $custom_field->save();
                        }
                        $exp2 = Event::find($event->id);
                        $exp2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                            date_interval_create_from_date_string($event->recur_frequency . ' ' . $event->recur_type . 's')),
                            'Y-m-d');
                        $exp2->save();
                    }
                } else {
                    if (date("Y-m-d") <= $event->recur_end_date) {
                        if ($event->recur_next_date == date("Y-m-d")) {
                            $exp1 = new Event();
                            $exp1->parent_id = $event->id;
                            $exp1->branch_id = $event->branch_id;
                            $exp1->user_id = $event->user_id;
                            $exp1->event_location_id = $event->event_location_id;
                            $exp1->event_calendar_id = $event->event_calendar_id;
                            $exp1->name = $event->name;
                            $exp1->cost = $event->cost;
                            $exp1->all_day = $event->all_day;
                            $exp1->start_date = $event->start_date;
                            $exp1->start_time = $event->start_time;
                            $exp1->end_date = $event->end_date;
                            $exp1->end_time = $event->end_time;
                            $exp1->checkin_type = $event->checkin_type;
                            $exp1->tags = $event->tags;
                            $exp1->include_checkout = $event->include_checkout;
                            $exp1->family_checkin = $event->family_checkin;
                            $exp1->featured_image = $event->featured_image;
                            $exp1->gallery = $event->gallery;
                            $exp1->files = $event->files;
                            $exp1->longitude = $event->longitude;
                            $exp1->latitude = $event->latitude;
                            $exp1->notes = $event->notes;
                            $date = explode('-', date("Y-m-d"));
                            $exp1->year = $date[0];
                            $exp1->month = $date[1];
                            $exp1->save();
                            $custom_fields = CustomFieldMeta::where('parent_id', $event->id)->where('category',
                                'events')->get();
                            foreach ($custom_fields as $key) {
                                $custom_field = new CustomFieldMeta();
                                $custom_field->name = $key->name;
                                $custom_field->parent_id = $exp1->id;
                                $custom_field->custom_field_id = $key->custom_field_id;
                                $custom_field->category = "events";
                                $custom_field->save();
                            }
                            $exp2 = Event::find($event->id);
                            $exp2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                                date_interval_create_from_date_string($event->recur_frequency . ' ' . $event->recur_type . 's')),
                                'Y-m-d');
                            $exp2->save();
                        }
                    }
                }
            }
            //check for recurring payroll
            $payrolls = Payroll::where('recurring', 1)->get();
            foreach ($payrolls as $payroll) {
                if (empty($payroll->recur_end_date)) {
                    if ($payroll->recur_next_date == date("Y-m-d")) {
                        $pay1 = new Payroll();
                        $pay1->payroll_template_id = $payroll->payroll_template_id;
                        $pay1->user_id = $payroll->user_id;
                        $pay1->employee_name = $payroll->employee_name;
                        $pay1->business_name = $payroll->business_name;
                        $pay1->payment_method = $payroll->payment_method;
                        $pay1->bank_name = $payroll->bank_name;
                        $pay1->account_number = $payroll->account_number;
                        $pay1->description = $payroll->description;
                        $pay1->comments = $payroll->comments;
                        $pay1->paid_amount = $payroll->paid_amount;
                        $date = explode('-', date("Y-m-d"));
                        $pay1->date = date("Y-m-d");
                        $pay1->year = $date[0];
                        $pay1->month = $date[1];
                        $pay1->save();
                        //save payroll meta
                        $metas = PayrollMeta::where('payroll_id',
                            $payroll->id)->get();;
                        foreach ($metas as $key) {
                            $meta = new PayrollMeta();
                            $meta->value = $key->value;
                            $meta->payroll_id = $pay1->id;
                            $meta->payroll_template_meta_id = $key->payroll_template_meta_id;
                            $meta->position = $key->position;
                            $meta->save();
                        }
                        $pay2 = Payroll::find($payroll->id);
                        $pay2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                            date_interval_create_from_date_string($payroll->recur_frequency . ' ' . $payroll->recur_type . 's')),
                            'Y-m-d');
                        $pay2->save();
                    } else {
                        if (date("Y-m-d") <= $payroll->recur_end_date) {
                            if ($payroll->recur_next_date == date("Y-m-d")) {
                                $pay1 = new Payroll();
                                $pay1->payroll_template_id = $payroll->payroll_template_id;
                                $pay1->user_id = $payroll->user_id;
                                $pay1->employee_name = $payroll->employee_name;
                                $pay1->business_name = $payroll->business_name;
                                $pay1->payment_method = $payroll->payment_method;
                                $pay1->bank_name = $payroll->bank_name;
                                $pay1->account_number = $payroll->account_number;
                                $pay1->description = $payroll->description;
                                $pay1->comments = $payroll->comments;
                                $pay1->paid_amount = $payroll->paid_amount;
                                $date = explode('-', date("Y-m-d"));
                                $pay1->date = date("Y-m-d");
                                $pay1->year = $date[0];
                                $pay1->month = $date[1];
                                $pay1->save();
                                //save payroll meta
                                $metas = PayrollMeta::where('payroll_id',
                                    $payroll->id)->get();;
                                foreach ($metas as $key) {
                                    $meta = new PayrollMeta();
                                    $meta->value = $key->value;
                                    $meta->payroll_id = $pay1->id;
                                    $meta->payroll_template_meta_id = $key->payroll_template_meta_id;
                                    $meta->position = $key->position;
                                    $meta->save();
                                }
                                $pay2 = Payroll::find($payroll->id);
                                $pay2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                                    date_interval_create_from_date_string($payroll->recur_frequency . ' ' . $payroll->recur_type . 's')),
                                    'Y-m-d');
                                $pay2->save();
                            }
                        }
                    }
                }
            }
            //check for recurring pledges
            $pledges = Pledge::where('recurring', 1)->get();
            foreach ($pledges as $pledge) {
                if (empty($pledge->recur_end_date)) {
                    if ($pledge->recur_next_date == date("Y-m-d")) {
                        $exp1 = new Pledge();
                        $exp1->branch_id = $pledge->branch_id;
                        $exp1->user_id = $pledge->user_id;
                        $exp1->member_id = $pledge->member_id;
                        $exp1->family_id = $pledge->family_id;
                        $exp1->pledge_type = $pledge->pledge_type;
                        $exp1->campaign_id = $pledge->campaign_id;
                        $exp1->amount = $pledge->amount;
                        $exp1->notes = $pledge->notes;
                        $exp1->date = date("Y-m-d");
                        $date = explode('-', date("Y-m-d"));
                        $exp1->year = $date[0];
                        $exp1->month = $date[1];
                        $exp1->save();
                        $custom_fields = CustomFieldMeta::where('parent_id', $pledge->id)->where('category',
                            'pledges')->get();
                        foreach ($custom_fields as $key) {
                            $custom_field = new CustomFieldMeta();
                            $custom_field->name = $key->name;
                            $custom_field->parent_id = $exp1->id;
                            $custom_field->custom_field_id = $key->custom_field_id;
                            $custom_field->category = "pledges";
                            $custom_field->save();
                        }
                        $exp2 = Pledge::find($pledge->id);
                        $exp2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                            date_interval_create_from_date_string($pledge->recur_frequency . ' ' . $pledge->recur_type . 's')),
                            'Y-m-d');
                        $exp2->save();
                    }
                } else {
                    if (date("Y-m-d") <= $pledge->recur_end_date) {
                        if ($pledge->recur_next_date == date("Y-m-d")) {
                            $exp1 = new Pledge();
                            $exp1->branch_id = $pledge->branch_id;
                            $exp1->user_id = $pledge->user_id;
                            $exp1->member_id = $pledge->member_id;
                            $exp1->family_id = $pledge->family_id;
                            $exp1->pledge_type = $pledge->pledge_type;
                            $exp1->campaign_id = $pledge->campaign_id;
                            $exp1->amount = $pledge->amount;
                            $exp1->notes = $pledge->notes;
                            $exp1->date = date("Y-m-d");
                            $date = explode('-', date("Y-m-d"));
                            $exp1->year = $date[0];
                            $exp1->month = $date[1];
                            $exp1->save();
                            $custom_fields = CustomFieldMeta::where('parent_id', $pledge->id)->where('category',
                                'pledges')->get();
                            foreach ($custom_fields as $key) {
                                $custom_field = new CustomFieldMeta();
                                $custom_field->name = $key->name;
                                $custom_field->parent_id = $exp1->id;
                                $custom_field->custom_field_id = $key->custom_field_id;
                                $custom_field->category = "pledges";
                                $custom_field->save();
                            }
                            $exp2 = Pledge::find($pledge->id);
                            $exp2->recur_next_date = date_format(date_add(date_create(date("Y-m-d")),
                                date_interval_create_from_date_string($pledge->recur_frequency . ' ' . $pledge->recur_type . 's')),
                                'Y-m-d');
                            $exp2->save();
                        }
                    }
                }
            }
            Setting::where('setting_key',
                'cron_last_run')->update(['setting_value' => date("Y-m-d H:i:s")]);
        }
    }

}
