<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Branch;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Email;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Member;
use App\Models\MemberTag;
use App\Models\Setting;
use App\Models\SoulWinning;
use App\Models\Tag;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class SoulWinningController extends Controller
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
        if (!Sentinel::hasAccess('members')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        return view('soul_winning.data');
    }

    public function get_soul_winnings(Request $request)
    {
        if (!Sentinel::hasAccess('members')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $query =SoulWinning::leftJoin('branches', 'branches.id', 'soul_winnings.branch_id')
            ->selectRaw('soul_winnings.*,branches.name branch');
        return DataTables::of($query)->editColumn('name', function ($data) {
            return '<a href="' . url('soul_winning/' . $data->id . '/show') . '" class="">' . $data->first_name . ' ' . $data->middle_name . ' ' . $data->last_name . '</a>';
        })->editColumn('age', function ($data) {
            return Carbon::now()->diffInYears(Carbon::parse($data->dob));
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('members.view')) {
                $action .= '<li><a href="' . url('soul_winning/' . $data->id . '/show') . '" class="">' . trans_choice('general.detail', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('members.update')) {
                $action .= '<li><a href="' . url('soul_winning/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('members.delete')) {
                $action .= '<li><a href="' . url('soul_winning/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('gender', function ($data) {
            if ($data->gender == "male") {
                return trans_choice('general.male', 1);
            }
            if ($data->gender == "female") {
                return trans_choice('general.female', 1);
            }
            if ($data->gender == "unknown") {
                return trans_choice('general.unknown', 1);
            }

        })->editColumn('photo', function ($data) {
            if (!empty($data->photo))
                return '<a href="' . asset('uploads/' . $data->photo) . '" class="fancybox"><img src="' . asset('uploads/' . $data->photo) . '" width="100"></a>';

        })->editColumn('id', function ($data) {
            return '<a href="' . url('soul_winning/' . $data->id . '/show') . '" class="">' . $data->id . '</a>';

        })->rawColumns(['id', 'name', 'action', 'photo'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('members.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        return view('soul_winning.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('members.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = new SoulWinning();
        $soul_winning->branch_id = $request->branch_id;
        $soul_winning->first_name = $request->first_name;
        $soul_winning->middle_name = $request->middle_name;
        $soul_winning->last_name = $request->last_name;
        $soul_winning->user_id = Sentinel::getUser()->id;
        $soul_winning->gender = $request->gender;
        $soul_winning->marital_status = $request->marital_status;
        $soul_winning->status = $request->status;
        $soul_winning->home_phone = $request->home_phone;
        $soul_winning->mobile_phone = $request->mobile_phone;
        $soul_winning->work_phone = $request->work_phone;
        if (!empty($request->dob)) {
            $soul_winning->dob = $request->dob;
        }

        $soul_winning->address = $request->address;
        $soul_winning->notes = $request->notes;
        $soul_winning->email = $request->email;
        if ($request->hasFile('photo')) {
            $file = array('photo' => $request->file('photo'));
            $rules = array('photo' => 'required|mimes:jpeg,jpg,bmp,png');
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $soul_winning->photo = $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move(public_path() . '/uploads',
                    $request->file('photo')->getClientOriginalName());
            }

        }
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
        $soul_winning->files = serialize($files);
        $soul_winning->save();
        //check for tags


        GeneralHelper::audit_trail("Added member  with id:" . $soul_winning->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('soul_winning/data');
    }


    public function show($id)
    {
        if (!Sentinel::hasAccess('members.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = SoulWinning::find($id);

        //get custom fields
        return view('soul_winning.show', compact('soul_winning'));
    }


    public function edit($id)
    {
        if (!Sentinel::hasAccess('members.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = SoulWinning::find($id);

        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] =  $key->name;
        }

        return view('soul_winning.edit', compact('soul_winning','branches'));
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
        if (!Sentinel::hasAccess('members.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        $soul_winning = SoulWinning::find($id);
        $soul_winning->branch_id = $request->branch_id;
        $soul_winning->first_name = $request->first_name;
        $soul_winning->middle_name = $request->middle_name;
        $soul_winning->last_name = $request->last_name;
        $soul_winning->gender = $request->gender;
        $soul_winning->marital_status = $request->marital_status;
        $soul_winning->status = $request->status;
        $soul_winning->home_phone = $request->home_phone;
        $soul_winning->mobile_phone = $request->mobile_phone;
        $soul_winning->work_phone = $request->work_phone;
        if (!empty($request->dob)) {
            $soul_winning->dob = $request->dob;
        }

        $soul_winning->address = $request->address;
        $soul_winning->notes = $request->notes;
        $soul_winning->email = $request->email;
        if ($request->hasFile('photo')) {
            $file = array('photo' =>  $request->file('photo'));
            $rules = array('photo' => 'required|mimes:jpeg,jpg,bmp,png');
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $soul_winning->photo = $request->file('photo')->getClientOriginalName();
                $request->file('photo')->move(public_path() . '/uploads',
                    $request->file('photo')->getClientOriginalName());
            }

        }
        $files = unserialize($soul_winning->files);
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
        $soul_winning->files = serialize($files);
        $soul_winning->save();

        GeneralHelper::audit_trail("Updated member  with id:" . $soul_winning->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('soul_winning/data');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('members.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        SoulWinning::destroy($id);
        GeneralHelper::audit_trail("Deleted member  with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('soul_winning/data');
    }

    public function deleteFile(Request $request, $id)
    {
        if (!Sentinel::hasAccess('members.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = SoulWinning::find($id);
        $files = unserialize($soul_winning->files);
        @unlink(public_path() . '/uploads/' . $files[$request->id]);
        $files = array_except($files, [$request->id]);
        $soul_winning->files = serialize($files);
        $soul_winning->save();


    }

    public function convert_to_member(Request $request, $id)
    {
        if (!Sentinel::hasAccess('members.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = SoulWinning::find($id);
        $member = new Member();
        $member->branch_id = $soul_winning->branch_id;
        $member->first_name = $soul_winning->first_name;
        $member->middle_name = $soul_winning->middle_name;
        $member->last_name = $soul_winning->last_name;
        $member->user_id = Sentinel::getUser()->id;
        $member->gender = $soul_winning->gender;
        $member->marital_status = $soul_winning->marital_status;
        $member->status = $soul_winning->status;
        $member->home_phone = $soul_winning->home_phone;
        $member->mobile_phone = $soul_winning->mobile_phone;
        $member->work_phone = $soul_winning->work_phone;
        if (!empty($soul_winning->dob)) {
            $member->dob = $soul_winning->dob;
        }
        $member->address = $soul_winning->address;
        $member->notes = $soul_winning->notes;
        $member->email = $soul_winning->email;
        $member->files = $soul_winning->files;
        $member->save();


        $soul_winning->member_id = $member->id;
        $soul_winning->save();
        GeneralHelper::audit_trail("Added member  with id:" . $member->id);

        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();
    }

    public function decline(Request $request, $id)
    {
        if (!Sentinel::hasAccess('members.approve')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = Member::find($id);
        $soul_winning->active = 0;
        $soul_winning->save();
        GeneralHelper::audit_trail("Declined borrower  with id:" . $soul_winning->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();
    }

    public function blacklist(Request $request, $id)
    {
        if (!Sentinel::hasAccess('members.blacklist')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = Member::find($id);
        $soul_winning->blacklisted = 1;
        $soul_winning->save();
        GeneralHelper::audit_trail("Blacklisted borrower  with id:" . $id);
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();
    }

    public function unBlacklist(Request $request, $id)
    {
        if (!Sentinel::hasAccess('members.blacklist')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $soul_winning = Member::find($id);
        $soul_winning->blacklisted = 0;
        $soul_winning->save();
        GeneralHelper::audit_trail("Undo Blacklist for borrower  with id:" . $id);
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();
    }


}
