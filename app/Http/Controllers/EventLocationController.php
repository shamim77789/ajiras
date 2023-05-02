<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Models\EventLocation;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class EventLocationController extends Controller
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
        $data = EventLocation::all();
        return view('location.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //get custom fields
        return view('location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $location = new EventLocation();
        $location->name = $request->name;
        $location->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/location/data');
    }


    public function show($location)
    {

        return view('location.show', compact('location'));
    }


    public function edit($location)
    {
        return view('location.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $location = EventLocation::find($id);
        $location->name = $request->name;
        $location->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/location/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        EventLocation::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('event/location/data');
    }

}
