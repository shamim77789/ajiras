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
use App\Models\State;
use App\Models\Dioces;

class StateController extends Controller
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
        $data = State::with(['dioces'])->get();
	
        return view('state.data', compact('data'));
	}
	
	public function create()
	{
		$dioces = Dioces::select('id','name')->get();
		return view('state.create')->with(compact('dioces'));
	}
	
	public function store(Request $request)
	{
		$state = new State();
		$state->dioces_id = $request->dioces_id;
		$state->name = $request->name;
		$state->save();
		Flash::success(trans('general.successfully_saved'));
        return redirect('state/data');
	}
	
	public function edit($id)
	{
        $state = State::find($id);
		$dioces = Dioces::select('id','name')->get();
        return view('state.edit', compact('state','dioces'));
	}
	
    public function update(Request $request, $id)
    {
        $state = State::find($id);
		$state->dioces_id = $request->dioces_id;
		$state->name = $request->name;
        $state->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('state/data');
    }
	
    public function delete($id)
    {
        State::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }
	
}
