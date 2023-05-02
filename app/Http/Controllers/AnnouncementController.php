<?php

namespace App\Http\Controllers;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use App\Models\Announcement;
use App\Models\AnnouncementType;
class AnnouncementController extends Controller
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
        $data = Announcement::with(['announcement_types'])->get();
        return view('announcement.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$announcement_type = AnnouncementType::all();
        return view('announcement.create')->with(compact('announcement_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$announcement = new Announcement();
		$announcement->name = $request->name;
		$announcement->current_date = $request->current_date;
		$announcement->announcement_type = $request->announcement_type;
		$announcement->announcement_date = $request->announcement_date;
		$announcement->notes = $request->notes;
		$announcement->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('announcement/data');
    }


    public function show($id)
    {		
		$announcement = Announcement::with(['announcement_types'])->where('id',$id)->first();

		return view('announcement.show', compact('announcement'));
    }


    public function edit($id)
    {
		$announcement = Announcement::find($id);
		$announcement_type = AnnouncementType::all();
        return view('announcement.edit')->with(compact('announcement_type','announcement'));
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
		$announcement = Announcement::find($id);
		$announcement->name = $request->name;		
		$announcement->current_date = $request->current_date;
		$announcement->announcement_type = $request->announcement_type;
		$announcement->announcement_date = $request->announcement_date;
		$announcement->notes = $request->notes;
		$announcement->save();
		
		Flash::success(trans('general.successfully_saved'));
        return redirect('announcement/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
		Announcement::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }
}
