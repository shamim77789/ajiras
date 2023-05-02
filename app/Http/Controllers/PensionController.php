<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\BulkSms;
use App\Helpers\GeneralHelper;
use App\Models\Borrower;
use App\Models\ExpenseType;
use App\Models\Branch;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Pension;
use App\Models\PensionMeta;
use App\Models\PensionTemplate;
use App\Models\PensionTemplateMeta;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use PDF;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Api\ClickatellHttp;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class PensionController extends Controller
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
        if (!Sentinel::hasAccess('pension')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        return view('pension.data');
    }
    public function get_pension(Request $request)
    {
        if (!Sentinel::hasAccess('pension')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        if (!empty($request->user_id)) {
            $user_id = $request->user_id;
        } else {
            $user_id = null;
        }


        $query = DB::table("pension")
            ->leftJoin("users", "users.id", "pension.user_id")
            ->selectRaw("users.first_name,users.last_name,pension.*,(SELECT SUM(value) FROM pension_meta WHERE pension_meta.pension_id=pension.id AND position='bottom_left') gross_amount,(SELECT SUM(value) FROM pension_meta WHERE pension_meta.pension_id=pension.id AND position='bottom_right') total_deductions")
            ->when($user_id, function ($query) use ($user_id) {
            $query->where("pension.user_id", $user_id);
        });
        return DataTables::of($query)->editColumn('user', function ($data) {
            return '<a href="' . url('user/' . $data->user_id . '/show') . '">' . $data->first_name . ' ' . $data->last_name . '</a>';
        })->editColumn('recurring', function ($data) {
            if ($data->recurring == 1) {
                return trans_choice('general.yes', 1);
            } else {
                return trans_choice('general.no', 1);
            }

        })->editColumn('gross_amount', function ($data) {
            return number_format($data->gross_amount, 2);
        })->editColumn('total_deductions', function ($data) {
            return number_format($data->total_deductions, 2);
        })->editColumn('net_amount', function ($data) {
            return number_format($data->gross_amount - $data->total_deductions, 2);
        })->editColumn('paid_amount', function ($data) {
            return number_format($data->paid_amount, 2);
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('pension.view')) {
                $action .= '<li><a href="' . url('pension/' . $data->id . '/payslip') . '" class="" target="_blank">' . trans_choice('general.payslip', 1) . '</a></li>';
            }
            if (Sentinel::hasAccess('pension.update')) {
                $action .= '<li><a href="' . url('pension/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('pension.delete')) {
                $action .= '<li><a href="' . url('pension/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('pension/' . $data->id . '/show') . '" class="">' . $data->id . '</a>';

        })->rawColumns(['id', 'user', 'action'])->make(true);
    }

    public function indexTemplate()
    {
        $data = PensionTemplate::all();
        return view('pension.pension_template.data', compact('data'));
    }

    public function editTemplate($id)
    {

        $top_left = PensionTemplateMeta::where('pension_template_id', $id)->where('position', 'top_left')->get();
        $top_right = PensionTemplateMeta::where('pension_template_id', $id)->where('position', 'top_right')->get();
        $bottom_left = PensionTemplateMeta::where('pension_template_id', $id)->where('position', 'bottom_left')->get();
        $bottom_right = PensionTemplateMeta::where('pension_template_id', $id)->where('position',
            'bottom_right')->get();
        return view('pension.pension_template.edit',
            compact('id', 'bottom_right', 'bottom_left', 'top_right', 'top_left'));
    }

    public function deleteTemplateMeta(Request $request)
    {
        PensionTemplateMeta::destroy($request->meta_id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('pension.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $users = User::all();
		$expense_type = ExpenseType::select('id','name')->get();
        $user = array();
        foreach ($users as $key) {
            $user[$key->id] = $key->first_name . ' ' . $key->last_name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'pension')->get();
        $template = PensionTemplate::first();
        $top_left = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'top_left')->get();
        $top_right = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'top_right')->get();
        $bottom_left = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'bottom_left')->get();
        $bottom_right = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'bottom_right')->get();
        return view('pension.create',
            compact('user', 'custom_fields', 'bottom_right', 'bottom_left', 'top_right', 'top_left', 'template','branches','expense_type'));
    }

    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('pension.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $pension = new Pension();
        $pension->pension_template_id = $request->pension_template_id;
        $pension->user_id = $request->user_id;
        $pension->branch_id = $request->branch_id;
        $pension->employee_name = $request->employee_name;
        $pension->business_name = $request->business_name;
        $pension->payment_method = $request->payment_method;
        $pension->bank_name = $request->bank_name;
        $pension->account_number = $request->account_number;
        $pension->description = $request->description;
        $pension->comments = $request->comments;
        $pension->paid_amount = $request->paid_amount;
        $pension->date = $request->date;
		$pension->expense_type = $request->expense_type;
        $date = explode('-', $request->date);
        $pension->recurring = $request->recurring;
        if ($request->recurring == 1) {
            $pension->recur_frequency = $request->recur_frequency;
            $pension->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $pension->recur_end_date = $request->recur_end_date;
            }

            $pension->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                'Y-m-d');

            $pension->recur_type = $request->recur_type;
        }
        $pension->year = $date[0];
        $pension->month = $date[1];
        $pension->save();
        //save pension meta
        $metas = PensionTemplateMeta::where('pension_template_id', $request->template_id)->get();
        foreach ($metas as $key) {
            $meta = new PensionMeta();
            $kid = $key->id;
            $meta->value = $request->$kid;
            $meta->pension_id = $pension->id;
            $meta->pension_template_meta_id = $key->id;
            $meta->position = $key->position;
            $meta->save();
        }
        GeneralHelper::audit_trail("Added pension with id:".$pension->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('pension/data');
    }

    public function pdfPayslip($id)
    {	
		$pension = Pension::where('id',$id)->first();
		
		$payslip_heading = Branch::with(['dioces','state'])->where('id', $pension->branch_id)->first();

		$top_left = PensionMeta::where('pension_id', $pension->id)->where('position',
            'top_left')->get();
        $top_right = PensionMeta::where('pension_id', $pension->id)->where('position',
            'top_right')->get();
        $bottom_left = PensionMeta::where('pension_id', $pension->id)->where('position',
            'bottom_left')->get();
        $bottom_right = PensionMeta::where('pension_id', $pension->id)->where('position',
            'bottom_right')->get();
        $pdf = PDF::loadView('pension.pdf_payslip',
            compact('pension', 'top_left', 'top_right', 'bottom_left', 'bottom_right','payslip_heading'));
        return $pdf->download($pension->employee_name . " - Payslip.pdf",
            'D');

    }

    public function staffPension($user)
    {
        if (!Sentinel::hasAccess('pension.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        return view('pension.staff_pension', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addTemplateRow(Request $request, $id)
    {
        $meta = new PensionTemplateMeta();
        $meta->name = $request->name;
        $meta->pension_template_id = $id;
        $meta->position = $request->position;
        $meta->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('pension/template/' . $id . '/edit');
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user->first_name . ' ' . $user->last_name;
    }

    public function show($borrower)
    {
        if (!Sentinel::hasAccess('pension.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $users = User::all();
        $user = array();
        foreach ($users as $key) {
            $user[$key->id] = $key->first_name . ' ' . $key->last_name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'borrowers')->get();
        return view('borrower.show', compact('borrower', 'user', 'custom_fields'));
    }


    public function edit($id)
    {
		$pension = Pension::where('id',$id)->first();

		if (!Sentinel::hasAccess('pension.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $users = User::all();
        $user = array();
        foreach ($users as $key) {
            $user[$key->id] = $key->first_name . ' ' . $key->last_name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'pension')->get();
        $template = PensionTemplate::first();
        $top_left = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'top_left')->get();
        $top_right = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'top_right')->get();
        $bottom_left = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'bottom_left')->get();
        $bottom_right = PensionTemplateMeta::where('pension_template_id', $template->id)->where('position',
            'bottom_right')->get();
		$expense_type = ExpenseType::select('id','name')->get();

		return view('pension.edit',
            compact('user', 'custom_fields', 'bottom_right', 'bottom_left', 'top_right', 'top_left', 'template',
                'pension','branches','expense_type'));
    }

    public function update(Request $request, $id)
    {
        if (!Sentinel::hasAccess('pension.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $pension = Pension::find($id);
        $pension->pension_template_id = $request->pension_template_id;
        $pension->user_id = $request->user_id;
        $pension->branch_id = $request->branch_id;
        $pension->employee_name = $request->employee_name;
        $pension->business_name = $request->business_name;
        $pension->payment_method = $request->payment_method;
        $pension->bank_name = $request->bank_name;
		$pension->expense_type = $request->expense_type;
        $pension->account_number = $request->account_number;
        $pension->description = $request->description;
        $pension->comments = $request->comments;
        $pension->paid_amount = $request->paid_amount;
        $pension->date = $request->date;
        $date = explode('-', $request->date);
        $pension->recurring = $request->recurring;
        if ($request->recurring == 1) {
            $pension->recur_frequency = $request->recur_frequency;
            $pension->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $pension->recur_end_date = $request->recur_end_date;
            }
            if (empty($pension->recur_next_date)) {
                $pension->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                    date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                    'Y-m-d');
            }
            $pension->recur_type = $request->recur_type;
        }
        $pension->year = $date[0];
        $pension->month = $date[1];
        $pension->save();
        //save pension meta
        $metas = PensionTemplateMeta::where('pension_template_id', $request->template_id)->get();;
        foreach ($metas as $key) {
            if (!empty(PensionMeta::where('pension_template_meta_id', $key->id)->where('pension_id',
                $id)->first())
            ) {
                $meta = PensionMeta::where('pension_template_meta_id', $key->id)->where('pension_id',
                    $id)->first();
            } else {
                $meta = new PensionMeta();
            }
            $kid = $key->id;
            $meta->value = $request->$kid;
            $meta->pension_id = $pension->id;
            $meta->pension_template_meta_id = $key->id;
            $meta->position = $key->position;
            $meta->save();
        }
        GeneralHelper::audit_trail("Updated pension with id:".$pension->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('pension/data');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateTemplate(Request $request, $id)
    {

        $metas = PensionTemplateMeta::where('pension_template_id', $id)->get();
        foreach ($metas as $key) {
            $meta = PensionTemplateMeta::find($key->id);
            $kid = $key->id;
            $meta->name = $request->$kid;
            $meta->save();
        }
        Flash::success(trans('general.successfully_saved'));
        return redirect('pension/template');
    }

    public function delete($id)
    {
        if (!Sentinel::hasAccess('pension.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        Pension::destroy($id);
        PensionMeta::where('pension_id',$id)->delete();
        GeneralHelper::audit_trail("Deleted pension with id:".$id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('pension/data');
    }

}
