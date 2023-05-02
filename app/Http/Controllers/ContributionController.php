<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;

use App\Models\Branch;
use App\Models\ContributionBatch;
use App\Models\ContributionType;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Contribution;
use App\Models\Fund;
use App\Models\Member;
use App\Models\PaymentMethod;
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
use App\Models\Deduction;
use App\Models\contributionDeduction;

class ContributionController extends Controller
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
        if (!Sentinel::hasAccess('contributions.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        return view('contribution.data');
    }

    public function get_contributions(Request $request)
    {
        if (!Sentinel::hasAccess('contributions')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $member_id = $request->member_id;
        $contribution_batch_id = $request->contribution_batch_id;
        $family_id = $request->family_id;
        $branch_id = $request->branch_id;
        $contribution_type_id = $request->contribution_type_id;
        $query = DB::table("contributions")
            ->leftJoin('branches', 'branches.id', 'contributions.branch_id')
            ->leftJoin('contribution_types', 'contribution_types.id', 'contributions.contribution_type_id')
            ->leftJoin('contribution_batches', 'contribution_batches.id', 'contributions.contribution_batch_id')
            ->leftJoin('payment_methods', 'payment_methods.id', 'contributions.payment_method_id')
            ->leftJoin('members', 'members.id', 'contributions.member_id')
            ->selectRaw("contributions.*,concat(members.first_name,' ',members.middle_name,' ',members.last_name) member,branches.name branch,contribution_types.name contribution_type,payment_methods.name payment_method,contribution_batches.name batch")
            ->when($member_id, function ($query) use ($member_id) {
                $query->where("contributions.member_id", $member_id);
            })
            ->when($contribution_batch_id, function ($query) use ($contribution_batch_id) {
                $query->where("contributions.contribution_batch_id", $contribution_batch_id);
            })
            ->when($family_id, function ($query) use ($family_id) {
                $query->where("contributions.family_id", $family_id);
            })
            ->when($branch_id, function ($query) use ($branch_id) {
                $query->where("contributions.branch_id", $branch_id);
            })
            ->when($contribution_type_id, function ($query) use ($contribution_type_id) {
                $query->where("contributions.contribution_type_id", $contribution_type_id);
            });
        return DataTables::of($query)->editColumn('member', function ($data) {
            if ($data->member_type == 0) {
                return trans('general.anonymous');
            } else {
                return '<a href="' . url('member/' . $data->id . '/show') . '" class="">' . $data->member . '</a>';
            }
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('contributions.view')) {
            }
            if (Sentinel::hasAccess('contributions.update')) {
                $action .= '<li><a href="' . url('contribution/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('contributions.delete')) {
                $action .= '<li><a href="' . url('contribution/' . $data->id . '/delete') . '" class="delete">' . trans_choice('general.delete', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('files', function ($data) {
            $content = "";
            foreach (unserialize($data->files) as $k => $value) {
                $content .= ' <li><a href="' . asset('uploads/' . $value) . '" target="_blank">' . $value . '</a></li>';
            }
            return $content;
        })->editColumn('batch', function ($data) {
            return '<a href="' . url('contribution/batch/' . $data->contribution_batch_id . '/show') . '" class="">' . $data->contribution_batch_id . '-' . $data->batch . '</a>';

        })->editColumn('amount', function ($data) {

            return number_format($data->amount);

        })->rawColumns(['id', 'member', 'action', 'batch', 'amount', 'files'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('contributions.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $branches = array();
        // $batches["0"] = trans_choice('new', 1) . ' ' . trans_choice('batch', 1);
        $branches_data = Branch::get();
        foreach ($branches_data as $key) {
            $branches[$key->id] =  $key->name;
        }
        $batches = array();
        // $batches["0"] = trans_choice('new', 1) . ' ' . trans_choice('batch', 1);
        $batches_data = ContributionBatch::where('status', 0)->get();
        foreach ($batches_data as $key) {
            $batches[$key->id] = $key->id . ' -' . $key->name;
        }
        $funds = array();
        $funds_data = Fund::all();
        foreach ($funds_data as $key) {
            $funds[$key->id] = $key->name;
        }
        $payment_methods = array();
        $payment_methods_data = PaymentMethod::all();
        foreach ($payment_methods_data as $key) {
            $payment_methods[$key->id] = $key->name;
        }
        $contribution_types = array();
        $contribution_types_data = ContributionType::all();
        foreach ($contribution_types_data as $key) {
            $contribution_types[$key->id] = $key->name;
        }
        $members = array();
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->member_number . ')';
        }
        \JavaScript::put([
            'batches' => $batches_data->keyBy('id'),
            'funds' => $funds_data->keyBy('id'),
            'payment_methods' => $payment_methods_data->keyBy('id'),
            'contribution_types' => $contribution_types_data->keyBy('id'),
            'branches' => $branches_data->keyBy('id'),
        ]);
        //get custom fields
        $custom_fields = CustomField::where('category', 'contributions')->get();
	
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


		
		
		return view('contribution.create', compact('batches', 'custom_fields', 'funds', 'payment_methods', 'members', 'branches','group_type','group_checkbox'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('contributions.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
		$data = $request->input();
        foreach ($request->selected_contributions as $key) 
		{

            $contribution = new Contribution();
            if (!empty($request->member_type)) 
			{
                $contribution->member_type = 0;
            } 
			else 
			{
                $contribution->member_type = 1;
                $contribution->member_id = $request->member_id;
            }


            $deductions = contributionDeduction::join('deductions', 'deductions.id', '=', 'contribution_deduction.deduction_id')
                ->where('contribution_deduction.contribution_type_id', $request->contribution_type_id[$key][0])
                ->get();

            $deduct = 0;

            foreach ($deductions as $v) {
                
                $deduct += ($request->amount[$key][0] * $v->amount) / 100;
            }

            $amount = ($request->amount[$key][0] - $deduct);

            $contribution->fund_id = $request->fund_id[$key][0];
            $contribution->branch_id = $request->branch_id;
            $contribution->family_id = $request->family_id;
            $contribution->contribution_type_id = $request->contribution_type_id[$key][0];
            $contribution->contribution_batch_id = $request->contribution_batch_id[$key][0];
            $contribution->payment_method_id = $request->payment_method_id[$key][0];
            $contribution->amount = $amount;
            $contribution->actual_amount = $request->amount[$key][0];
            $contribution->notes = $request->notes;
            $contribution->date = $request->date;
            $date = explode('-', $request->date);
            $contribution->year = $date[0];
            $contribution->month = $date[1];
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
            $contribution->files = serialize($files);
            $contribution->save();
            $custom_fields = CustomField::where('category', 'contribution')->get();
            foreach ($custom_fields as $key) {
                $custom_field = new CustomFieldMeta();
                $id = $key->id;
                $custom_field->name = $request->$id;
                $custom_field->parent_id = $contribution->id;
                $custom_field->custom_field_id = $key->id;
                $custom_field->category = "contribution";
                $custom_field->save();
            }
        }
		$dNow = date('y-m-d H:i:s');
		
		if(array_key_exists('group',$data))
		{
			foreach($data['group'] as $key => $group)
			{
				DB::table('contribution_group')
			    ->insert([
							'contribution_id' => $contribution->id,
							'group_id' => $group,
							'created_at' => $dNow
				
						]);
			}				
		}
		
		GeneralHelper::audit_trail("Added contribution with id:" . $contribution->id);
        Flash::success(trans('general.successfully_saved'));
        if (isset($request->return_url)) {
            return redirect($request->return_url);
        }
        if (!empty($request->save_return)) {
            return redirect()->back();
        }
        if (!empty($request->save_exit)) {
            return redirect('contribution/data');
        }

        return redirect('contribution/data');
    }


    public function show($contribution)
    {
        if (!Sentinel::hasAccess('contributions.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $users = User::all();
        $user = array();
        foreach ($users as $key) {
            $user[$key->id] = $key->first_name . ' ' . $key->last_name;
        }
        //get custom fields
        $custom_fields = CustomField::where('category', 'contributions')->get();
        return view('contribution.show', compact('contribution', 'user', 'custom_fields'));
    }


    public function edit($contribution)
    {
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $branches = array();
        // $batches["0"] = trans_choice('new', 1) . ' ' . trans_choice('batch', 1);
        $branches_data = Branch::get();
        foreach ($branches_data as $key) {
            $branches[$key->id] = $key->name;
        }
        $batches = array();
        // $batches["0"] = trans_choice('new', 1) . ' ' . trans_choice('batch', 1);
        $batches_data = ContributionBatch::where('status', 0)->get();
        foreach ($batches_data as $key) {
            $batches[$key->id] = $key->id . ' -' . $key->name;
        }
        $funds = array();
        $funds_data = Fund::all();
        foreach ($funds_data as $key) {
            $funds[$key->id] = $key->name;
        }
        $payment_methods = array();
        $payment_methods_data = PaymentMethod::all();
        foreach ($payment_methods_data as $key) {
            $payment_methods[$key->id] = $key->name;
        }
        $contribution_types = array();
        $contribution_types_data = ContributionType::all();
        foreach ($contribution_types_data as $key) {
            $contribution_types[$key->id] = $key->name;
        }
        $members = array();
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
        }
        \JavaScript::put([
            'batches' => $batches_data->keyBy('id'),
            'funds' => $funds_data->keyBy('id'),
            'payment_methods' => $payment_methods_data->keyBy('id'),
            'contribution_types' => $contribution_types_data->keyBy('id'),
            'branches' => $branches_data->keyBy('id'),
        ]);
	
		
		$contribution_group = DB::table('contribution_group as c')
							  ->where('contribution_id', $contribution->id)
							  ->select('group_id')
							  ->get();

		$contribution_group = json_decode(json_encode($contribution_group), true);

		$contribute_group = array();
		
		if(!empty($contribution_group))
		{
			foreach($contribution_group as $group)
			{
				$contribute_group[] = $group['group_id'];
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
					$group_checkbox .= '<input type="checkbox" class="checkbox_class" name="group[]" value="'.$group->id.'" '.(in_array($group->id,$contribute_group) ? 'checked' : '').'>';			
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
        $custom_fields = CustomField::where('category', 'contributions')->get();
        return view('contribution.edit',
            compact('contribution', 'batches', 'custom_fields', 'funds', 'payment_methods', 'members', 'contribution_types', 'branches','group_checkbox'));
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
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
		}
		$data = $request->input();
		
        $contribution = Contribution::find($id);
        if (!empty($request->member_type)) {
            $contribution->member_type = 0;
        } else {
            $contribution->member_type = 1;
            $contribution->member_id = $request->member_id;
        }
        $contribution->branch_id = $request->branch_id;
        $contribution->fund_id = $request->fund_id;
        $contribution->family_id = $request->family_id;
        $contribution->contribution_type_id = $request->contribution_type_id;
        $contribution->contribution_batch_id = $request->contribution_batch_id;
        $contribution->payment_method_id = $request->payment_method_id;
        $contribution->amount = $request->amount;
        $contribution->notes = $request->notes;
        $contribution->date = $request->date;
        $date = explode('-', $request->date);
        $contribution->year = $date[0];
        $contribution->month = $date[1];
        $files = unserialize($contribution->files);
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
        $contribution->files = serialize($files);
        $contribution->save();
        $custom_fields = CustomField::where('category', 'contributions')->get();
        foreach ($custom_fields as $key) {
            if (!empty(CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id', $id)->where('category',
                'contributions')->first())
            ) {
                $custom_field = CustomFieldMeta::where('custom_field_id', $key->id)->where('parent_id',
                    $id)->where('category', 'contributions')->first();
            } else {
                $custom_field = new CustomFieldMeta();
            }
            $kid = $key->id;
            $custom_field->name = $request->$kid;
            $custom_field->parent_id = $id;
            $custom_field->custom_field_id = $key->id;
            $custom_field->category = "contributions";
            $custom_field->save();
        }

		$dNow = date('y-m-d H:i:s');
		DB::table('contribution_group')->where('contribution_id', $contribution->id)->delete();
		if(array_key_exists('group',$data))
		{
			foreach($data['group'] as $key => $group)
			{
				DB::table('contribution_group')
			    ->insert([
							'contribution_id' => $contribution->id,
							'group_id' => $group,
							'created_at' => $dNow
				
						]);
			}				
		}		
		
		GeneralHelper::audit_trail("Updated contribution with id:" . $contribution->id);
        Flash::success(trans('general.successfully_saved'));
        if (isset($request->return_url)) {
            return redirect($request->return_url);
        }
        return redirect('contribution/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        if (!Sentinel::hasAccess('contributions.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        Contribution::destroy($id);
        GeneralHelper::audit_trail("Deleted contribution with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        if (isset($request->return_url)) {
            return redirect($request->return_url);
        }
        return redirect('contribution/data');
    }

    public function deleteFile(Request $request, $id)
    {
        if (!Sentinel::hasAccess('contributions.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $contribution = Contribution::find($id);
        $files = unserialize($contribution->files);
        @unlink(public_path() . '/uploads/' . $files[$request->id]);
        $files = array_except($files, [$request->id]);
        $contribution->files = serialize($files);
        $contribution->save();
    }
	
	public function get_member_groups(Request $request)
	{
		$member_id = $request->member_id;
		
		$group_members = DB::table('group_members')->select('group_id')->where('member_id', $member_id)->get();
		$group_members = json_decode(json_encode($group_members), true);
		return $group_members;
	}

}
