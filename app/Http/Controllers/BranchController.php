<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use App\Models\Dioces;
use App\Models\State;

class BranchController extends Controller
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
        $data = Branch::with(['dioces','state'])->get();
        return view('branch.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //get custom fields
		
		$dioces = Dioces::select('id','name')->get();
		
        return view('branch.create')->with(compact('dioces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $branch = new Branch();
        $branch->name = $request->name;
		$branch->dioces_id = $request->dioces_id;
		$branch->state_id = $request->state_id;
        $branch->notes = $request->notes;
        $branch->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('branch/data');
    }


    public function show($id)
    {
        $branch = Branch::find($id);

        $users = User::all();
		
        return view('branch.show', compact('branch', 'users'));
    }


    public function edit($id)
    {
        $branch = Branch::find($id);
		$dioces = Dioces::select('id','name')->get();
        return view('branch.edit', compact('branch','dioces'));
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
        $branch = Branch::find($id);
        $branch->name = $request->name;
		$branch->dioces_id = $request->dioces_id;
		$branch->state_id = $request->state_id;
        $branch->notes = $request->notes;
        $branch->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('branch/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Branch::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }

    public function add_user(Request $request, $id)
    {
        if (BranchUser::where('user_id', $request->user_id)->where('branch_id', $id)->get()->count() > 0) {
            Flash::warning(trans_choice("branch::general.user_already_added_to_branch", 1));
            return redirect()->back();
        }
        $branch_user = new BranchUser();
        $branch_user->branch_id = $id;
        $branch_user->user_id = $request->user_id;
        //$branch_user->created_by_id = Auth::id();
        $branch_user->save();
        Flash::success(trans_choice("core::general.successfully_saved", 1));
        return redirect()->back();
    }

    public function remove_user($id)
    {
        BranchUser::destroy($id);
        Flash::success(trans_choice("core::general.successfully_deleted", 1));
        return redirect()->back();
    }
	
	
	public function get_state(Request $request)
	{
		$dioces_id = $request->input('dioces_id');
		
		$states = State::where('dioces_id', $dioces_id)->select('id','name')->get();
		$states = json_decode(json_encode($states), true);
		return $states;
	}


	
}
