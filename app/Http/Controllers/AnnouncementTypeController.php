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
class AnnouncementTypeController extends Controller
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
		$data = AnnouncementType::all();
		
        return view('announcement_type.data')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('announcement_type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$announcement_name = $request->announcement_name;
		$announcement_type_id = $request->announcement_type_id;
		
		if($announcement_type_id == '0')
		{
			$announcement_type = new AnnouncementType();
			
			$counter = AnnouncementType::where('name', $announcement_name)->count();
			if($counter > 0) return response()->json(['status' => '302']);
		}
		else
		{
			$announcement_type = AnnouncementType::find($announcement_type_id);
		}

		$announcement_type->name = $announcement_name;
		$announcement_type->save();	
		
		return response()->json(['status' => 200]);
//		Flash::success(trans('general.successfully_saved'));
//        return redirect('announcement_type/data');
    }


    public function show($id)
    {		
        return view('announcement_type.show', compact('branch', 'users'));
    }


    public function edit($id)
    {
        return view('announcement.edit');
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
        Flash::success(trans('general.successfully_saved'));
        return redirect('announcement_type/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
		AnnouncementType::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }
}
