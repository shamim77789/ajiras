<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;

use App\Models\Branch;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Email;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Member;
use App\Models\MembersTransfer;
use App\Models\Dioces;
use App\Models\MemberTag;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class MemberTransferController extends Controller
{
    public function __construct()
    {
//        $this->middleware('groups');

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
		if (!Sentinel::hasAccess('members')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		$members_transfer =MembersTransfer::with(['member','member_from_branch','member_to_branch','dioce'])->get();

		return view('members_transfer.data')->with(compact('members_transfer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('members.create')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		
		$members = Member::get();
		$dioces = Dioces::get();
		$user_id = Sentinel::getUser()->id;		

		if($user_id == '1')
			$branches = Branch::get();
		else
			$branches = Branch::with(['user_branches'])->where('user_id', $user_id)->get();

//		$branches = Branch::get();
		
		return view('members_transfer.create', compact('members', 'dioces','branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
	 public function store(Request $request)
	 {
		 if (!Sentinel::hasAccess('members.create')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }		
		 $member_transfer = new MembersTransfer();
		 $member_transfer->member_id = $request->member_id;
//		 $member_transfer->member_number = $request->member_number;
		 $member_transfer->transfer_from_branch = $request->transfer_from_branch;
		 $member_transfer->dioces = $request->dioces;
		 $member_transfer->transfer_to_branch = $request->transfer_to_branch;
		 $member_transfer->transfer_date = $request->transfer_date;
		 $member_transfer->reason = $request->reason;
		 $member_transfer->save();
		 Flash::success(trans('general.successfully_saved'));
         return redirect('member/transfer/data');
		 
	 }

    public function edit($id)
    {
        if (!Sentinel::hasAccess('members.create')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		$member_transfer = MembersTransfer::find($id);
		$members = Member::get();
		$dioces = Dioces::get();
//		$branches = Branch::get();
		$user_id = Sentinel::getUser()->id;		

		if($user_id == '1')
			$branches = Branch::get();
		else
			$branches = Branch::with(['user_branches'])->where('user_id', $user_id)->get();
		
		return view('members_transfer.edit', compact('members', 'dioces','branches','member_transfer'));
    }	

	 public function update(Request $request, $id)
	 {
		 if (!Sentinel::hasAccess('members.create')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }		
		 	
		 $member_transfer = MembersTransfer::find($id);
		 $member_transfer->member_id = $request->member_id;
//		 $member_transfer->member_number = $request->member_number;
		 $member_transfer->transfer_from_branch = $request->transfer_from_branch;
		 $member_transfer->dioces = $request->dioces;
		 $member_transfer->transfer_to_branch = $request->transfer_to_branch;
		 $member_transfer->transfer_date = $request->transfer_date;
		 $member_transfer->reason = $request->reason;
		 $member_transfer->save();
		 Flash::success(trans('general.successfully_saved'));
         return redirect('member/transfer/data');
		 
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
        MembersTransfer::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('member/transfer/data');
    }

	public function dioces_get_branches(Request $request)
	{
		$dioces = $request->dioces;
		$branches = Branch::where('dioces_id', $dioces)->get();
		return $branches;
	}

	public function status(Request $request)
	{
		$status = $request->status;
		$id = $request->member_transfer_id;
		$member_transfer = MembersTransfer::find($id);
		$member_transfer->member_status = $status;
		$member_transfer->save();
		
	}

}
