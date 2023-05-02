<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\BulkSms;
use App\Helpers\GeneralHelper;
use App\Models\Borrower;

use App\Models\Branch;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Api\ClickatellHttp;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Fund;
use App\Models\Contribution;
use App\Models\ContributionType;
use App\Models\OtherIncome;
use App\Models\OtherIncomeType;

class ExpenseController extends Controller
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
        if (!Sentinel::hasAccess('expenses')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        return view('expense.data');
    }

    public function get_expenses(Request $request)
    {
        if (!Sentinel::hasAccess('expenses')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        $query = DB::table("expenses")
            ->leftJoin('branches', 'branches.id', 'expenses.branch_id')
            ->leftJoin("expense_types", "expense_types.id", "expenses.expense_type_id")
            ->selectRaw("expense_types.name expense_type,expenses.*,branches.name branch");
        return DataTables::of($query)->editColumn('amount', function ($data) {
            return number_format($data->amount, 2);
        })->editColumn('recurring', function ($data) {
            if ($data->recurring == 1) {
                return trans_choice('general.yes', 1);
            } else {
                return trans_choice('general.no', 1);
            }

        })->editColumn('files', function ($data) {
            $content = "";
            foreach (unserialize($data->files) as $k => $value) {
                $content .= ' <li><a href="' . asset('uploads/' . $value) . '" target="_blank">' . $value . '</a></li>';
            }
            return $content;
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('expenses.update')) {
                $action .= '<li><a href="' . url('expense/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('expenses.delete')) {
                $action .= '<li><a href="' . url('expense/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('expense/' . $data->id . '/show') . '" class="">' . $data->id . '</a>';

        })->rawColumns(['id', 'files', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('expenses.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $types = array();
        foreach (ExpenseType::all() as $key) {
            $types[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'expenses')->get();
        return view('expense.create', compact('types', 'custom_fields', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('expenses.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $expense = new Expense();
        $expense->expense_type_id = $request->expense_type_id;
        $expense->branch_id = $request->branch_id;
        $expense->amount = $request->amount;
        $expense->notes = $request->notes;
        $expense->date = $request->date;
        $date = explode('-', $request->date);
        $expense->recurring = $request->recurring;
        if ($request->recurring == 1) {
            $expense->recur_frequency = $request->recur_frequency;
            $expense->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $expense->recur_end_date = $request->recur_end_date;
            }

            $expense->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                'Y-m-d');

            $expense->recur_type = $request->recur_type;
        }
        $expense->year = $date[0];
        $expense->month = $date[1];
        $files = array();
        if ($request->hasFile('files')) {
            $count = 0;
            foreach ($request->file('files') as $key) {
                $file = array('files' => $key);
                $rules = array('files' => 'required|mimes:jpeg,jpg,bmp,png,pdf,docx,xlsx');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $files[$count] = $key->getClientOriginalName();
                    $key->move(public_path() . '/uploads',
                        $key->getClientOriginalName());
                }
                $count++;
            }
        }
        $expense->files = serialize($files);
        //files
        $expense->save();
        $custom_fields = CustomField::where('category', 'expenses')->get();
        foreach ($custom_fields as $key) {
            $custom_field = new CustomFieldMeta();
            $id = $key->id;
            $custom_field->name = $request->$id;
            $custom_field->parent_id = $expense->id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "expenses";
            $custom_field->save();
        }
        GeneralHelper::audit_trail("Added expense with id:" . $expense->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('expense/data');
    }


    public function show($borrower)
    {
        if (!Sentinel::hasAccess('expenses.view')) {
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


    public function edit($expense)
    {
        $types = array();
        foreach (ExpenseType::all() as $key) {
            $types[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'expenses')->get();
        return view('expense.edit', compact('expense', 'types', 'custom_fields', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Sentinel::hasAccess('expenses.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $expense = Expense::find($id);
        $expense->expense_type_id = $request->expense_type_id;
        $expense->branch_id = $request->branch_id;
        $expense->amount = $request->amount;
        $expense->notes = $request->notes;
        $expense->date = $request->date;
        $date = explode('-', $request->date);
        $expense->recurring = $request->recurring;
        if ($request->recurring == 1) {
            $expense->recur_frequency = $request->recur_frequency;
            $expense->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $expense->recur_end_date = $request->recur_end_date;
            }
            if (empty($expense->recur_next_date)) {
                $expense->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                    date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                    'Y-m-d');
            }
            $expense->recur_type = $request->recur_type;
        }
        $expense->year = $date[0];
        $expense->month = $date[1];
        $files = unserialize($expense->files);
        $count = count($files);
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key) {
                $count++;
                $file = array('files' => $key);
                $rules = array('files' => 'required|mimes:jpeg,jpg,bmp,png,pdf,docx,xlsx');
                $validator = Validator::make($file, $rules);
                if ($validator->fails()) {
                    Flash::warning(trans('general.validation_error'));
                    return redirect()->back()->withInput()->withErrors($validator);
                } else {
                    $files[$count] = $key->getClientOriginalName();
                    $key->move(public_path() . '/uploads',
                        $key->getClientOriginalName());
                }

            }
        }
        $expense->files = serialize($files);
        $expense->save();
        $custom_fields = CustomField::where('category', 'expenses')->get();
        foreach ($custom_fields as $key) {
            if (!empty(CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id', $id)->where('category',
                'expenses')->first())
            ) {
                $custom_field = CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id',
                    $id)->where('category', 'expenses')->first();
            } else {
                $custom_field = new CustomFieldMeta();
            }
            $kid = $key->id;
            $custom_field->name = $request->$kid;
            $custom_field->parent_id = $id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "expenses";
            $custom_field->save();
        }
        GeneralHelper::audit_trail("Updated expense with id:" . $expense->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('expense/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('expenses.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        Expense::destroy($id);
        GeneralHelper::audit_trail("Deleted expense with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('expense/data');
    }

    //expense type
    public function indexType()
    {
        $data = ExpenseType::all();

        return view('expense.type.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createType()
    {

        $otherIncome = OtherIncomeType::get();
        $fund = Fund::get();
        $ContributionType = ContributionType::get();

        return view('expense.type.create', compact('otherIncome', 'fund', 'ContributionType'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeType(Request $request)
    {
        $type = new ExpenseType();
        $type->name = $request->input('name');

        if($request->input('expense_source') === 'fund'){

            $type->fund_id = $request->input('expense');

        }else if($request->input('expense_source') === 'contributiontype'){

            $type->contribution_type_id = $request->input('expense');

        }else if($request->input('expense_source') === 'otherincome'){

            $type->otherincome_type_id = $request->input('expense');

        }
        $type->save();

		if(!empty($request->estimation_amount))
		{
			$estimation_amount = $request->estimation_amount;
			foreach($estimation_amount as $key => $value)
			{
				DB::table('expense_types_estimation')
				->insert([
							'expense_type_id' => $type->id,
							'estimation_amount' => $request->estimation_amount[$key],
							'year' => $request->year[$key],
							'quarter' => $request->quarter[$key]
						 ]);
				
			}
		}		
		
		Flash::success(trans('general.successfully_saved'));
        return redirect('expense/type/data');
    }

    public function editType($expense_type)
    {
        $otherIncome = OtherIncomeType::get();
        $fund = Fund::get();
        $ContributionType = ContributionType::get();
		
		$expense_types_estimation = DB::table('expense_types_estimation')->where('expense_type_id', $expense_type->id)->get();
        return view('expense.type.edit', compact('expense_type', 'otherIncome', 'ContributionType', 'fund','expense_types_estimation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateType(Request $request, $id)
    {
        $type = ExpenseType::find($id);
        $type->name = $request->name;
        if($request->input('expense_source') === 'fund'){

            $type->fund_id = $request->input('expense');
            $type->contribution_type_id = null;
            $type->otherincome_type_id = null;

        }else if($request->input('expense_source') === 'contributiontype'){

            $type->contribution_type_id = $request->input('expense');
            $type->fund_id = null;
            $type->otherincome_type_id = null;

        }else if($request->input('expense_source') === 'otherincome'){

            $type->contribution_type_id = null;
            $type->fund_id = null;
            $type->otherincome_type_id = $request->input('expense');

        }
        $type->save();
		
		DB::table('expense_types_estimation')->where('expense_type_id', $type->id)->delete();
		if(!empty($request->estimation_amount))
		{
			$estimation_amount = $request->estimation_amount;
			foreach($estimation_amount as $key => $value)
			{
				DB::table('expense_types_estimation')
				->insert([
							'expense_type_id' => $type->id,
							'estimation_amount' => $request->estimation_amount[$key],
							'year' => $request->year[$key],
							'quarter' => $request->quarter[$key]
						 ]);
				
			}
		}		

		Flash::success(trans('general.successfully_saved'));
        return redirect('expense/type/data');
    }

    public function deleteType($id)
    {
        ExpenseType::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('expense/type/data');
    }

    public function deleteFile(Request $request, $id)
    {
        $expense = Expense::find($id);
        $files = unserialize($expense->files);
        @unlink(public_path() . '/uploads/' . $files[$request->id]);
        $files = array_except($files, [$request->id]);
        $expense->files = serialize($files);
        $expense->save();


    }
}
