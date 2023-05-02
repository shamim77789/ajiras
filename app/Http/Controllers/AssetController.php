<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\AssetValuation;
use App\Models\Borrower;

use App\Models\Branch;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;

use App\Models\Setting;
use App\Models\SoulWinning;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Api\ClickatellHttp;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
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
        if (!Sentinel::hasAccess('assets')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $data = Asset::all();

        return view('asset.data', compact('data'));
    }

    public function get_assets(Request $request)
    {
        if (!Sentinel::hasAccess('assets')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $query = Asset::leftJoin('branches', 'branches.id', 'assets.branch_id')
            ->leftJoin('asset_types', 'asset_types.id', 'assets.asset_type_id')
            ->selectRaw('assets.*,branches.name branch,asset_types.name asset_type');
        return DataTables::of($query)
            ->editColumn('action', function ($data) {
                $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
                if (Sentinel::hasAccess('assets.view')) {
                    // $action .= '<li><a href="' . url('asset/' . $data->id . '/show') . '" class="">' . trans_choice('general.detail', 2) . '</a></li>';
                }
                if (Sentinel::hasAccess('assets.update')) {
                    $action .= '<li><a href="' . url('asset/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
                }
                if (Sentinel::hasAccess('assets.delete')) {
                    $action .= '<li><a href="' . url('asset/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
                }
                $action .= "</ul></div>";
                return $action;
            })
//            ->editColumn('asset_type', function ($data) {
//                if ($data->asset_type == "current") {
//                    return trans_choice('general.current', 1) . ' ' . trans_choice('general.asset', 1);
//                }
//                if ($data->asset_type == "fixed") {
//                    return trans_choice('general.fixed', 1) . ' ' . trans_choice('general.asset', 1);
//                }
//                if ($data->asset_type == "intangible") {
//                    return trans_choice('general.intangible', 1) . ' ' . trans_choice('general.asset', 1);
//                }
//                if ($data->asset_type == "investment") {
//                    return trans_choice('general.investment', 1) . ' ' . trans_choice('general.asset', 1);
//                }
//                if ($data->asset_type == "other") {
//                    return trans_choice('general.other', 1) . ' ' . trans_choice('general.asset', 1);
//                }
//
//            })
            ->editColumn('files', function ($data) {
                $html = '<ul class="">';
                foreach (unserialize($data->files) as $k => $value) {
                    $html .= '<li><a href="' . asset('uploads/' . $value) . '" target="_blank">' . $value . '</a></li>';
                }
                $html .= "</ul>";
                return $html;

            })
            ->editColumn('id', function ($data) {
                return '<a href="#" class="">' . $data->id . '</a>';

            })
            ->rawColumns(['id', 'name', 'action', 'files'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('assets.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $types = array();
        foreach (AssetType::all() as $key) {
            $types[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'assets')->get();
        return view('asset.create', compact('types', 'custom_fields', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('assets.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $asset = new Asset();
        $asset->user_id = Sentinel::getUser()->id;
        $asset->asset_type_id = $request->asset_type_id;
        $asset->branch_id = $request->branch_id;
        $asset->purchase_date = $request->purchase_date;
        $asset->purchase_price = $request->purchase_price;
        $asset->replacement_value = $request->replacement_value;
        $asset->serial_number = $request->serial_number;
        $asset->notes = $request->notes;
        $files = array();
        if (!empty($request->file('files'))) {
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
        $asset->files = serialize($files);
        //files
        $asset->save();
        //save asset valuation
        if (!empty($request->asset_management_current_date)) {
            $count = count($request->asset_management_current_date);
            for ($i = 0; $i < $count; $i++) {
                $valuation = new AssetValuation();
                $valuation->user_id = Sentinel::getUser()->id;
                $valuation->asset_id = $asset->id;
                $valuation->date = $request->asset_management_current_date[$i];
                $valuation->amount = $request->asset_management_current_value[$i];
                $valuation->save();
            }

        }
        $custom_fields = CustomField::where('category', 'assets')->get();
        foreach ($custom_fields as $key) {
            $custom_field = new CustomFieldMeta();
            $id = $key->id;
            $custom_field->name = $request->$id;
            $custom_field->parent_id = $asset->id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "assets";
            $custom_field->save();
        }
        GeneralHelper::audit_trail("Added asset  with id:" . $asset->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('asset/data');
    }


    public function show($borrower)
    {
        if (!Sentinel::hasAccess('assets.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
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


    public function edit($asset)
    {
        $types = array();
        foreach (AssetType::all() as $key) {
            $types[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'assets')->get();
        return view('asset.edit', compact('asset', 'types', 'custom_fields', 'branches'));
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
        if (!Sentinel::hasAccess('assets.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $asset = Asset::find($id);
        $asset->asset_type_id = $request->asset_type_id;
        $asset->branch_id = $request->branch_id;
        $asset->purchase_date = $request->purchase_date;
        $asset->purchase_price = $request->purchase_price;
        $asset->replacement_value = $request->replacement_value;
        $asset->serial_number = $request->serial_number;
        $asset->notes = $request->notes;
        $files = unserialize($asset->files);
        $count = count($files);
        if (!empty($request->file('files'))) {
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
        $asset->files = serialize($files);
        $asset->save();
        $custom_fields = CustomField::where('category', 'assets')->get();
        foreach ($custom_fields as $key) {
            if (!empty(CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id', $id)->where('category',
                'assets')->first())
            ) {
                $custom_field = CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id',
                    $id)->where('category', 'assets')->first();
            } else {
                $custom_field = new CustomFieldMeta();
            }
            $kid = $key->id;
            $custom_field->name = $request->$kid;
            $custom_field->parent_id = $id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "assets";
            $custom_field->save();
        }
        //delete current valuations
        AssetValuation::where('asset_id', $id)->delete();
        //save asset valuation
        if (!empty($request->asset_management_current_date)) {
            $count = count($request->asset_management_current_date);
            for ($i = 0; $i < $count; $i++) {
                $valuation = new AssetValuation();
                $valuation->user_id = Sentinel::getUser()->id;
                $valuation->asset_id = $id;
                $valuation->date = $request->asset_management_current_date[$i];
                $valuation->amount = $request->asset_management_current_value[$i];
                $valuation->save();
            }

        }
        GeneralHelper::audit_trail("Updated asset  with id:" . $asset->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('asset/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('assets.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        Asset::destroy($id);
        GeneralHelper::audit_trail("Deleted asset  with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('asset/data');
    }

    //expense type
    public function indexType()
    {
        $data = AssetType::all();

        return view('asset.type.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createType()
    {

        return view('asset.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeType(Request $request)
    {
        $type = new AssetType();
        $type->name = $request->name;
        //$type->type = $request->type;
        $type->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('asset/type/data');
    }

    public function editType($asset_type)
    {
        return view('asset.type.edit', compact('asset_type'));
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
        $type = AssetType::find($id);
        $type->name = $request->name;
        //$type->type = $request->type;
        $type->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('asset/type/data');
    }

    public function deleteType($id)
    {
        AssetType::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('asset/type/data');
    }

    public function deleteFile(Request $request, $id)
    {
        $asset = Asset::find($id);
        $files = unserialize($asset->files);
        @unlink(public_path() . '/uploads/' . $files[$request->id]);
        $files = array_except($files, [$request->id]);
        $asset->files = serialize($files);
        $asset->save();


    }
}
