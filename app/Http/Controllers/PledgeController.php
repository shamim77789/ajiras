<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;
use App\Models\ContributionBatch;
use App\Models\Branch;
use App\Models\Campaign;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Member;
use App\Models\Pledge;
use App\Models\PledgePayment;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class PledgeController extends Controller
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
        if (!Sentinel::hasAccess('pledges')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        return view('pledge.data');
    }

    public function get_pledges(Request $request)
    {
        if (!Sentinel::hasAccess('pledges')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        if (!empty($request->member_id)) {
            $member_id = $request->member_id;
        } else {
            $member_id = null;
        }
        if (!empty($request->campaign_id)) {
            $campaign_id = $request->campaign_id;
        } else {
            $campaign_id = null;
        }
        if (!empty($request->family_id)) {
            $family_id = $request->family_id;
        } else {
            $family_id = null;
        }
        $query = DB::table("pledges")
            ->leftJoin('branches', 'branches.id', 'pledges.branch_id')
            ->leftJoin('campaigns', 'campaigns.id', 'pledges.campaign_id')
            ->leftJoin('members', 'members.id', 'pledges.member_id')
            ->selectRaw("pledges.*,concat(members.first_name,' ',members.middle_name,' ',members.last_name) member,branches.name branch,campaigns.name campaign,(SELECT SUM(amount) FROM pledge_payments WHERE pledge_payments.pledge_id=pledges.id) payments")
            ->when($member_id, function ($query) use ($member_id) {
                $query->where("campaigns.member_id", $member_id);
            })
            ->when($campaign_id, function ($query) use ($campaign_id) {
                $query->where("campaigns.campaign_id", $campaign_id);
            })
            ->when($family_id, function ($query) use ($family_id) {
                $query->where("campaigns.family_id", $family_id);
            });
        return DataTables::of($query)->editColumn('member', function ($data) {
            return '<a href="' . url('member/' . $data->id . '/show') . '" class="">' . $data->member . '</a>';
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('pledges.view')) {
                $action .= '<li><a href="' . url('pledge/' . $data->id . '/show') . '" class="">' . trans_choice('general.detail', 2) . '</a></li>';
                $action .= '<li><a href="' . url('pledge/' . $data->id . '/payment/data') . '" class="">' . trans_choice('general.payment', 2) . '</a></li>';

            }
            if (Sentinel::hasAccess('pledges.update')) {
                $action .= '<li><a href="' . url('pledge/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('pledges.delete')) {
                $action .= '<li><a href="' . url('pledge/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('pledge/' . $data->id . '/show') . '" class="">' . $data->id . '</a>';

        })->editColumn('amount', function ($data) {
            $details = null;
            $details .= "<b>" . trans_choice('general.pledged', 1) . ':</b>' . number_format($data->amount) . '</br>';
            $details .= "<b>" . trans_choice('general.paid', 1) . ':</b>' . number_format($data->payments) . '</br>';

            return $details;

        })->editColumn('campaign', function ($data) {
            return '<a href="' . url('pledge/campaign/' . $data->campaign_id . '/show') . '" class="">' . $data->campaign . '</a>';

        })->rawColumns(['id', 'member', 'action', 'campaign', 'amount'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('pledges.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        $campaigns = array();
        foreach (Campaign::where('status', 0)->get() as $key) {
            $campaigns[$key->id] = $key->name;
        }
        $members = array();
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . ' (' . $key->member_number . ')';
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'pledges')->get();

		$batches = ContributionBatch::select('id','name')->get();
		
		$group_type = DB::table('groups as g')
					  ->join('group_type as gt','gt.id','g.group_type')
					  ->select(
								'gt.group_type as group_type',
								'gt.id as id'
		 					  )
					  ->groupBy('gt.group_type')
					  ->orderBy('gt.id','asc')
					  ->get();

		$group_checkbox = '';
		
		if(!empty($group_type))
		{
			$group_checkbox .= '<div class="container">';
			$group_checkbox .= '<div class="row">';
			
			foreach($group_type as $type)
			{
				$group_checkbox .= '<div class="col-lg-4 col-sm-12">';	
				$group_checkbox .= '<label>'.$type->group_type.'</label>';
				$groups = DB::table('groups as g')
						  ->join('group_type as gt','gt.id','g.group_type')
						  ->select(
									'g.id as id',
									'g.group_name as group_name',
									'gt.id as group_type_id',
									'gt.group_type as group_type'
								  )
						  ->where('gt.id', $type->id)
						  ->get();
				foreach($groups as $group)
				{
					$group_checkbox .= '<div class="checkbox">';			
					$group_checkbox .= '<label class="">';			
					$group_checkbox .= '<div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">';				$group_checkbox .= '<input type="checkbox" class="checkbox_class" name="group[]" value="'.$group->id.'">';			
					$group_checkbox .= '<ins class="iCheck-helper ins_class" style=""></ins>';			
					$group_checkbox .= '</div>';			
					$group_checkbox .= '<span style="margin-left:10px;">'.$group->group_name.'</span>';			
					$group_checkbox .= '</label>';			
					$group_checkbox .= '</div>';
				}
				$group_checkbox .= '</div>';			

			}
			$group_checkbox .= '</div>';			
			$group_checkbox .= '</div>';			

		}
		
		
		return view('pledge.create', compact('campaigns', 'custom_fields', 'members', 'branches','group_checkbox','batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('pledges.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		$data = $request->input();
        $pledge = new Pledge();
        $pledge->user_id = Sentinel::getUser()->id;
        $pledge->branch_id = $request->branch_id;
        $pledge->member_id = $request->member_id;
        $pledge->campaign_id = $request->campaign_id;
        $pledge->amount = $request->amount;
        $pledge->notes = $request->notes;
        $pledge->date = $request->date;
		$pledge->batches = $request->batches;
        $date = explode('-', $request->date);
        $pledge->recurring = $request->recurring;
		if (!empty($request->member_type)) 
		{
			$pledge->member_type = 0;
		}
		else
		{
			$pledge->member_type = 1;
		}
		if ($request->recurring == 1) {
            $pledge->recur_frequency = $request->recur_frequency;
            $pledge->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $pledge->recur_end_date = $request->recur_end_date;
            }

            $pledge->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                'Y-m-d');

            $pledge->recur_type = $request->recur_type;
        }
        $pledge->year = $date[0];
        $pledge->month = $date[1];
        $pledge->save();
        $custom_fields = CustomField::where('category', 'pledges')->get();
        foreach ($custom_fields as $key) {
            $custom_field = new CustomFieldMeta();
            $id = $key->id;
            $custom_field->name = $request->$id;
            $custom_field->parent_id = $pledge->id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "pledges";
            $custom_field->save();
        }
		$dNow = date('y-m-d H:i:s');
		
		if(array_key_exists('group',$data))
		{
			foreach($data['group'] as $key => $group)
			{
				DB::table('pledges_group')
			    ->insert([
							'pledge_id' => $pledge->id,
							'group_id' => $group,
							'created_at' => $dNow
				
						]);
			}				
		}		
		
		
		GeneralHelper::audit_trail("Added pledge with id:" . $pledge->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('pledge/data');
    }


    public function show($pledge)
    {
        if (!Sentinel::hasAccess('pledges.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'pledges')->get();
        return view('pledge.show', compact('pledge', 'custom_fields'));
    }


    public function edit($pledge)
    {
        if (!Sentinel::hasAccess('pledges.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        $campaigns = array();
        foreach (Campaign::where('status', 0)->get() as $key) {
            $campaigns[$key->id] = $key->name;
        }
        $members = array();
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
        }
		$batches = ContributionBatch::select('id','name')->get();

		$pledges_group = DB::table('pledges_group')
							  ->where('pledge_id', $pledge->id)
							  ->select('group_id')
							  ->get();

		$pledges_group = json_decode(json_encode($pledges_group), true);

		$pledge_group = array();
		
		if(!empty($pledges_group))
		{
			foreach($pledges_group as $group)
			{
				$pledge_group[] = $group['group_id'];
			}
		}

		$group_type = DB::table('groups as g')
					  ->join('group_type as gt','gt.id','g.group_type')
					  ->select(
								'gt.group_type as group_type',
								'gt.id as id'
		 					  )
					  ->groupBy('gt.group_type')
					  ->orderBy('gt.id','asc')
					  ->get();

		$group_checkbox = '';
		
		if(!empty($group_type))
		{
			$group_checkbox .= '<div class="container">';
			$group_checkbox .= '<div class="row">';
			
			foreach($group_type as $type)
			{
				$group_checkbox .= '<div class="col-lg-4 col-sm-12">';	
				$group_checkbox .= '<label>'.$type->group_type.'</label>';
				$groups = DB::table('groups as g')
						  ->join('group_type as gt','gt.id','g.group_type')
						  ->select(
									'g.id as id',
									'g.group_name as group_name',
									'gt.id as group_type_id',
									'gt.group_type as group_type'
								  )
						  ->where('gt.id', $type->id)
						  ->get();
				foreach($groups as $group)
				{
					$group_checkbox .= '<div class="checkbox">';			
					$group_checkbox .= '<label class="">';			
					$group_checkbox .= '<div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">';				
					$group_checkbox .= '<input type="checkbox" class="checkbox_class" name="group[]" value="'.$group->id.'" '.(in_array($group->id,$pledge_group) ? 'checked' : '').'>';			
					$group_checkbox .= '<ins class="iCheck-helper ins_class" style=""></ins>';			
					$group_checkbox .= '</div>';			
					$group_checkbox .= '<span style="margin-left:10px;">'.$group->group_name.'</span>';			
					$group_checkbox .= '</label>';			
					$group_checkbox .= '</div>';
				}
				$group_checkbox .= '</div>';			

			}
			$group_checkbox .= '</div>';			
			$group_checkbox .= '</div>';			

		}				
		
		
		//get custom fields
        $custom_fields = CustomField::where('category', 'pledges')->get();
        return view('pledge.edit', compact('pledge', 'campaigns', 'custom_fields', 'members', 'branches','group_checkbox','batches'));
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
        if (!Sentinel::hasAccess('pledges.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		
		$data = $request->input();
		
        $pledge = Pledge::find($id);
        $pledge->branch_id = $request->branch_id;
        $pledge->member_id = $request->member_id;
        $pledge->campaign_id = $request->campaign_id;
        $pledge->amount = $request->amount;
        $pledge->notes = $request->notes;
        $pledge->date = $request->date;
		$pledge->batches = $request->batches;
		$date = explode('-', $request->date);
        $pledge->recurring = $request->recurring;
		if (!empty($request->member_type)) 
		{
			$pledge->member_type = 0;
		}
		else
		{
			$pledge->member_type = 1;
		}		
		if ($request->recurring == 1) {
            $pledge->recur_frequency = $request->recur_frequency;
            $pledge->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $pledge->recur_end_date = $request->recur_end_date;
            }
            if (empty($pledge->recur_next_date)) {
                $pledge->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                    date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                    'Y-m-d');
            }
            $pledge->recur_type = $request->recur_type;
        }
        $pledge->year = $date[0];
        $pledge->month = $date[1];
        $pledge->save();
        $custom_fields = CustomField::where('category', 'pledges')->get();
        foreach ($custom_fields as $key) {
            if (!empty(CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id', $id)->where('category',
                'pledges')->first())
            ) {
                $custom_field = CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id',
                    $id)->where('category', 'pledges')->first();
            } else {
                $custom_field = new CustomFieldMeta();
            }
            $kid = $key->id;
            $custom_field->name = $request->$kid;
            $custom_field->parent_id = $id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "pledges";
            $custom_field->save();
        }

		$dNow = date('y-m-d H:i:s');
		DB::table('pledges_group')->where('pledge_id', $pledge->id)->delete();
		if(array_key_exists('group',$data))
		{
			foreach($data['group'] as $key => $group)
			{
				DB::table('pledges_group')
			    ->insert([
							'pledge_id' => $pledge->id,
							'group_id' => $group,
							'created_at' => $dNow
				
						]);
			}				
		}		
		GeneralHelper::audit_trail("Updated pledge with id:" . $pledge->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('pledge/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('pledges.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        Pledge::destroy($id);
        PledgePayment::where('pledge_id', $id)->delete();
        GeneralHelper::audit_trail("Deleted pledge with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('pledge/data');
    }
	public function get_member_groups(Request $request)
	{
		$member_id = $request->member_id;
		
		$group_members = DB::table('group_members')->select('group_id')->where('member_id', $member_id)->get();
		$group_members = json_decode(json_encode($group_members), true);
		return $group_members;
	}

}
