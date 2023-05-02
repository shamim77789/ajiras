<?php

namespace App\Http\Controllers;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use App\Models\AnnouncementType;
use App\Models\Dioces;
use App\Models\State;
use App\Models\Branch;
use App\Models\UserStates;
use DB;
class GeneralController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel');
    }
	
	public function states(Request $request)
	{
		$user_id = Sentinel::getUser()->id;

		$dioces_id = $request->dioces_id;
		
		if($user_id == '1')
		{
			$state = State::where('dioces_id', $dioces_id)->get();
		}
		else
		{
			$state = DB::table('user_states as us')
					 ->join('dioces as d','d.id', 's.dioces_id')
				     ->join('states as s','s.id','us.state_id')
					 ->where('us.user_id', $user_id)
				     ->where('d.id',$dioces_id)
				     ->select('s.id as id', 's.name as name')
				     ->get();
		}

		
		
		return $state;	
	}

	public function branches(Request $request)
	{
		$state_id = $request->state_id;
		$user_id = Sentinel::getUser()->id;
		
		if($user_id == '1')
			$branch = Branch::where('state_id', $state_id)->get();
		else
			$branch = DB::table('user_branches as ub')
					  ->join('branches as b','b.id','ub.branch_id')
				      ->join('states as s','s.id','b.state_id')
					  ->where('ub.user_id', $user_id)
					  ->where('s.id', $state_id)
 				      ->get();
		return $branch;
		
	}
}