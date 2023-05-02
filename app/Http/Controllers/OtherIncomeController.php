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
use App\Models\OtherIncome;
use App\Models\OtherIncomeType;
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

class OtherIncomeController extends Controller
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
        if (!Sentinel::hasAccess('other_income')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        return view('other_income.data');
    }

    public function get_other_income(Request $request)
    {
        if (!Sentinel::hasAccess('other_income')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        $query = DB::table("other_income")
            ->leftJoin('branches', 'branches.id', 'other_income.branch_id')
            ->leftJoin("other_income_types", "other_income_types.id", "other_income.other_income_type_id")
            ->selectRaw("other_income_types.name other_income_type,other_income.*,branches.name branch");
        return DataTables::of($query)->editColumn('amount', function ($data) {
            return number_format($data->amount, 2);
        })->editColumn('files', function ($data) {
            $content = "";
            foreach (unserialize($data->files) as $k => $value) {
                $content .= ' <li><a href="' . asset('uploads/' . $value) . '" target="_blank">' . $value . '</a></li>';
            }
            return $content;
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('other_income.update')) {
                $action .= '<li><a href="' . url('other_income/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('other_income.delete')) {
                $action .= '<li><a href="' . url('other_income/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('other_income/' . $data->id . '/show') . '" class="">' . $data->id . '</a>';

        })->rawColumns(['id', 'files', 'action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('other_income.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $types = array();
        foreach (OtherIncomeType::all() as $key) {
            $types[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'other_income')->get();
        return view('other_income.create', compact('types', 'custom_fields','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('other_income.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $other_income = new OtherIncome();
        $other_income->other_income_type_id = $request->other_income_type_id;
        $other_income->branch_id = $request->branch_id;
        $other_income->amount = $request->amount;
        $other_income->notes = $request->notes;
        $other_income->date = $request->date;
        $date = explode('-', $request->date);
        $other_income->year = $date[0];
        $other_income->month = $date[1];
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
        $other_income->files = serialize($files);
        $other_income->save();
        $custom_fields = CustomField::where('category', 'other_income')->get();
        foreach ($custom_fields as $key) {
            $custom_field = new CustomFieldMeta();
            $id = $key->id;
            $custom_field->name = $request->$id;
            $custom_field->parent_id = $other_income->id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "other_income";
            $custom_field->save();
        }
        GeneralHelper::audit_trail("Added other income with id:" . $other_income->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('other_income/data');
    }


    public function show($other_income)
    {
        if (!Sentinel::hasAccess('other_income.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $users = User::all();
        $user = array();
        foreach ($users as $key) {
            $user[$key->id] = $key->first_name . ' ' . $key->last_name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'other_income')->get();
        return view('other_income.show', compact('other_income', 'user', 'custom_fields'));
    }


    public function edit($other_income)
    {
        if (!Sentinel::hasAccess('other_income.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $types = array();
        foreach (OtherIncomeType::all() as $key) {
            $types[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'other_income')->get();
        return view('other_income.edit', compact('other_income', 'types', 'custom_fields','branches'));
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
        if (!Sentinel::hasAccess('other_income.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $other_income = OtherIncome::find($id);
        $other_income->other_income_type_id = $request->other_income_type_id;
        $other_income->branch_id = $request->branch_id;
        $other_income->amount = $request->amount;
        $other_income->notes = $request->notes;
        $other_income->date = $request->date;
        $date = explode('-', $request->date);
        $other_income->year = $date[0];
        $other_income->month = $date[1];
        $files = unserialize($other_income->files);
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
        $other_income->files = serialize($files);
        $other_income->save();
        $custom_fields = CustomField::where('category', 'other_income')->get();
        foreach ($custom_fields as $key) {
            if (!empty(CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id', $id)->where('category',
                'other_income')->first())
            ) {
                $custom_field = CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id',
                    $id)->where('category', 'other_income')->first();
            } else {
                $custom_field = new CustomFieldMeta();
            }
            $kid = $key->id;
            $custom_field->name = $request->$kid;
            $custom_field->parent_id = $id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "other_income";
            $custom_field->save();
        }
        GeneralHelper::audit_trail("Updated other income with id:" . $other_income->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('other_income/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('other_income.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        OtherIncome::destroy($id);
        GeneralHelper::audit_trail("Deleted other income with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('other_income/data');
    }

    public function deleteFile(Request $request, $id)
    {
        if (!Sentinel::hasAccess('other_income.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $other_income = OtherIncome::find($id);
        $files = unserialize($other_income->files);
        @unlink(public_path() . '/uploads/' . $files[$request->id]);
        $files = array_except($files, [$request->id]);
        $other_income->files = serialize($files);
        $other_income->save();


    }

    //expense type
    public function indexType()
    {
        $data = OtherIncomeType::all();

        return view('other_income.type.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createType()
    {

        return view('other_income.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeType(Request $request)
    {
        $type = new OtherIncomeType();
        $type->name = $request->name;
        $type->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('other_income/type/data');
    }

    public function editType($other_income_type)
    {
        return view('other_income.type.edit', compact('other_income_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateType(Request $request, $id)
    {
        $type = OtherIncomeType::find($id);
        $type->name = $request->name;
        $type->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('other_income/type/data');
    }

    public function deleteType($id)
    {
        OtherIncomeType::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('other_income/type/data');
    }
}
