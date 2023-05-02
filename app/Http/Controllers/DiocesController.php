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

class DiocesController extends Controller
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
	public function create()
	{
		return view('dioces.create');
	}
	
	public function store(Request $request)
	{
		$dioces = new Dioces();
		$dioces->name = $request->name;
		$dioces->save();
		Flash::success(trans('general.successfully_saved'));
        return redirect('dioces/data');
	}
	
	public function index()
	{
        $data = Dioces::all();
        return view('dioces.data', compact('data'));
	}
	
	public function edit($id)
	{
        $dioces = Dioces::find($id);
        return view('dioces.edit', compact('dioces'));
	}
	
    public function update(Request $request, $id)
    {
        $dioces = Dioces::find($id);
        $dioces->name = $request->name;
        $dioces->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('dioces/data');
    }
	
    public function delete($id)
    {
        Dioces::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }
	
}
