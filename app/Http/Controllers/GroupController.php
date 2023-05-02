<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aloha\Twilio\Twilio;
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
use App\Models\Tag;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;
class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel');

    }
	
	public function index()
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		
		$group_type = DB::table('group_type')
					  ->select('id','group_type')
					  ->get();
		$group_type = json_decode(json_encode($group_type), true);

        return view('groups.data')->with(compact('group_type'));
	}
	
	public function get_groups(Request $request)
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $query =DB::table('groups as g')
				->join('group_type as gt','gt.id','g.group_type')
				->select(
			   				'g.id as id',
							'g.group_name as group_name',
							'gt.group_type as group_type',
							'gt.id as group_type_id'
						)
				->get();
        return DataTables::of($query)->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('Groups.view')) {
                $action .= '<li><a href="' . url('Groups/'. $data->id.'/details') . '" class="details">Details</a></li>';
            }
			if (Sentinel::hasAccess('Groups.view')) {
                $action .= '<li><a href="' . url('Groups/' . $data->id . '/edit') . '" class="edit">Edit</a></li>';
            }
            if (Sentinel::hasAccess('Groups.view')) {
                $action .= '<li><a href="' . url('Groups/' . $data->id . '/delete') . '" class="delete">Delete</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->rawColumns(['id', 'name', 'action', 'group_type'])->make(true);
		
	}
	
	/*Create Group*/
	public function create(Request $request)
	{
		$group_types = DB::table('group_type')
					  ->select('id','group_type')
					  ->get();
	
		return view('groups.add')->with(compact('group_types'));
	}
	
	/*Create Group*/
	public function create_group(Request $request)
	{
		$data = $request->input();
		$group_type = $data['group_type'];
		$group_name = $data['group_name'];
		$dNow = date('y-m-d H:i:s');
		
		$group = array();
		$group['group_name'] = $group_name;
		$group['group_type'] = $group_type;
		$group['created_at'] = $dNow;
		
		$group_id = DB::table('groups')->insertGetId($group);
		
		GeneralHelper::audit_trail("Added Group  with id:" . $group_id);
        Flash::success(trans('Group Added Successfully'));
        return redirect('Groups/data');
	}
	
	/*Group Type*/
	public function type(Request $request)
	{
        if (!Sentinel::hasAccess('Groups')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		$branches = DB::table('branches')->select('id','name')->get();

        return view('groups.type')->with(compact('branches'));
	}
	
	/*Get Group Type*/
	public function get_group_types()
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $query =DB::table('group_type as gt')
				->join('branches as b','b.id','gt.branch_id')
				->select(
			   				'gt.id as id',
							'gt.group_type as group_type',
							'b.name as branch_name'
						)
				->get();
        return DataTables::of($query)->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('Groups.type')) {
                $action .= '<li><a href="' . url('Groups/' . $data->id . '/edit_group_type') . '" >Edit Group Type</a></li>';
            }
            if (Sentinel::hasAccess('Groups.type')) {
                $action .= '<li><a href="' . url('Groups/' . $data->id . '/delete_group_type') . '" class="delete">Delete Group Type</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->rawColumns(['id', 'group_type', 'action'])->make(true);
	}
	
	/*Edit Group*/
	public function edit(Request $request, $id)
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		
		$group_types = DB::table('group_type')
					  ->select('id','group_type')
					  ->get();
		
		$groups = DB::table('groups')
				  ->where('id',$id)
				  ->select('id','group_name','group_type')
				  ->first();
		

        return view('groups.edit')->with(compact('group_types','groups'));

	}
	
	
	/*Create Group Type*/
	public function create_type(Request $request)
	{
		$group_type = $request->input('group_type');
		$created_at = date('y-m-d H:i:s');
		$type = array();
		$type['group_type'] = $group_type;
		$type['created_at'] = $created_at;
 		
		DB::table('group_type')->insert($type);
	}
	/*Update Group*/
	public function update_group(Request $request, $id)
	{
		$data = $request->input();
		$group_type = $data['group_type'];
		$group_name = $data['group_name'];
		$group_id = $id;
		$dNow = date('y-m-d H:i:s');
		
		$group = array();
	
		$group['group_name'] = $group_name;
		$group['group_type'] = $group_type;
		$group['created_at'] = $dNow;
	
		DB::table('groups')->where('id', $group_id)->update($group);
		GeneralHelper::audit_trail("Updated Group Type  with id:" . $group_id);
        Flash::success(trans('Group Updated Successfully'));	
		return redirect('Groups/data');
	}
	
	/*Delete Group*/
	public function delete_group(Request $request, $id)
	{
		DB::table('groups')->where('id', $id)->delete();
        GeneralHelper::audit_trail("Deleted group  with id:" . $id);
        Flash::success(trans('Group Deleted Successfully'));
		return redirect()->back();
	}
	
	/*Delete Group Type*/
	public function delete_group_type(Request $request, $id)
	{
		DB::table('group_type')->where('id', $id)->delete();
		DB::table('groups')->where('group_type', $id)->delete();
        GeneralHelper::audit_trail("Deleted group type with id:" . $id);
        Flash::success(trans('Group Type Deleted Successfully'));
		return redirect()->back();
	}
	
	/*Add Group Type*/
	public function add_type()
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		$branches = DB::table('branches')->select('id','name')->get();

        return view('groups.add_type')->with(compact('branches'));
	}
	
	/*Group Type Operation*/
	public function create_group_type(Request $request)
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

		$group_type = $request->input('group_type');
		$branch_id = $request->input('branch_id');

		$group_type = DB::table('group_type')->insertGetId([
							'group_type' => $group_type,
							'branch_id' => $branch_id,
							'created_at' => date('Y-m-d H:i:s')
					 ]);
		GeneralHelper::audit_trail("Added Group Type  with id:" . $group_type);
        Flash::success(trans('Group Type Added Successfully'));

		return redirect('Groups/type');
		
	}
	
	/*Edit Group Type*/
	public function edit_group_type(Request $request, $id)
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		
		$group_types = DB::table('group_type')
					  ->where('id',$id)
					  ->first();
		$branches = DB::table('branches')->select('id','name')->get();
		return view('groups.edit_type')->with(compact('group_types','branches'));
	}
	
	/*Update Group Type*/
	public function update_group_type(Request $request)
	{
		$id = $request->input('id');
		$group_type = $request->input('group_type');
		$branch_id = $request->input('branch_id');

		if (!Sentinel::hasAccess('Groups')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }		
		DB::table('group_type')->where('id', $id)->update(['group_type' => $group_type,'branch_id' => $branch_id]);		
		GeneralHelper::audit_trail("Update Group Type  with id:" . $id);
        Flash::success(trans('Group Type Updated Successfully'));

		return redirect('Groups/type');
	}
	
	/*Group details*/
	public function details(Request $request, $id)
	{
		if (!Sentinel::hasAccess('Groups')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }		
		
		$groups = DB::table('groups as g')
				  ->where('g.id',$id)
				  ->join('group_type as gt','gt.id','g.group_type')
				  ->select(
							'gt.group_type as group_type',
							'g.group_name as group_name',
							'g.created_at as created_at'
						  )
				  ->first();
		
		$group_members = DB::table('group_members as gm')
						 ->where('gm.group_id', $id)
						 ->join('members as m','m.id','gm.member_id')
						 ->join('groups as g','g.id','gm.group_id')
						 ->join('group_type as gt','gt.id','g.group_type')
						 ->select(
									'g.group_name as group_name',
									'gt.group_type as group_type',
									'm.member_number as member_number',
									'm.first_name as first_name',
									'm.middle_name as middle_name',
									'm.last_name as last_name',
									'm.mobile_phone as mobile_phone',
									'm.email as email',
									'gm.id as id'
								 )						 
						 ->get();		
		
		return view('groups.view')->with(compact('group_members','groups'));

		
	}
	
	/*Groups Details Data*/
	public function get_group_details(Request $request, $id)
	{
        if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

		
		return DataTables::of($query)->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('Groups.type')) {
                $action .= '<li><a href="' . url('Groups/edit_group_type?user_id=' . $data->id) . '" >Edit Group Type</a></li>';
            }
            if (Sentinel::hasAccess('Groups.type')) {
                $action .= '<li><a href="' . url('Groups/' . $data->id . '/delete_group_type') . '" class="delete">Delete Group Type</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->rawColumns(['id','group_name', 'group_type','member_number', 'action'])->make(true);
	
	}
	
	/*Group Detail View*/
	public function detail_member_view(Request $request, $id)
	{
		if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
		}
		
		$groups = DB::table('groups as g')
				  ->join('group_members as gm','gm.group_id','g.id')
				  ->join('group_type as gt','gt.id','g.group_type')
				  ->where('gm.id',$id)
				  ->select(
							'gt.group_type as group_type',
							'g.group_name as group_name',
							'g.created_at as created_at'
						  )
				  ->first();		
		
	
		
		$get_member_data = DB::table('group_members as gm')
						   ->join('members as m','m.id','gm.member_id')
						   ->join('groups as g','g.id','gm.group_id')
						   ->join('group_type as gt','gt.id','g.group_type')
						   ->where('gm.id', $id)
						   ->select(
									'g.group_name as group_name',
									'gt.group_type as group_type',
									'm.member_number as member_number',
									'm.first_name as first_name',
									'm.middle_name as middle_name',
									'm.last_name as last_name',
									'm.mobile_phone as mobile_phone',
									'm.email as email',
									'gm.id as id'
								   )
						   ->first();
		
		return view('groups.detail_member_view')->with(compact('get_member_data','groups'));
	}
	
	/*Edit Member*/
	public function edit_member(Request $request, $id)
	{
		if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
		}
		
		$group = DB::table('groups')
				 ->select('id','group_name')
				 ->get();
		
		$groups = DB::table('groups as g')
				  ->join('group_members as gm','gm.group_id','g.id')
				  ->join('group_type as gt','gt.id','g.group_type')
				  ->where('gm.id',$id)
				  ->select(
							'gt.group_type as group_type',
							'g.group_name as group_name',
							'g.created_at as created_at'
						  )
				  ->first();		
		
	
		
		$get_member_data = DB::table('group_members as gm')
						   ->join('members as m','m.id','gm.member_id')
						   ->join('groups as g','g.id','gm.group_id')
						   ->join('group_type as gt','gt.id','g.group_type')
						   ->where('gm.id', $id)
						   ->select(
									'g.group_name as group_name',
									'gt.group_type as group_type',
									'm.member_number as member_number',
									'm.first_name as first_name',
									'm.middle_name as middle_name',
									'm.last_name as last_name',
									'm.mobile_phone as mobile_phone',
									'm.email as email',
									'gm.id as id',
									'g.id as group_id'
								   )
						   ->first();		
	
		return view('groups.detail_member_edit')->with(compact('get_member_data','groups','group'));

	}
	
	/*Delete Group Member*/
	public function remove_member(Request $request, $id)
	{
		if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
		}	
		
		DB::table('group_members')->where('id',$id)->delete();
		
		GeneralHelper::audit_trail("Member Deleted Successfully From Group with id:" . $id);
		Flash::success(trans('Member Deleted Successfully'));
		return redirect()->back();

		
	}
	
	/*Update Group Member */
	public function update_member_group(Request $request, $id)
	{
		$data = $request->input();
		$group_id = $data['group_name'];
		if (!Sentinel::hasAccess('Groups')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
		}	
		
		DB::table('group_members')->where('id', $id)->update(['group_id'=> $group_id]);

		GeneralHelper::audit_trail("Members Group Updated Successfully From Group with id:" . $id);
		Flash::success(trans('Member Group Updated Successfully'));
		return redirect()->back();
		
	}
	
}

?>