<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Models\EventCalendar;
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

class EventCalendarController extends Controller
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
        $data = EventCalendar::all();
        return view('calendar.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //get custom fields
        return view('calendar.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $calendar = new EventCalendar();
        $calendar->name = $request->name;
        $calendar->color = $request->color;
        $calendar->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/calendar/data');
    }


    public function show($calendar)
    {

        return view('calendar.show', compact('calendar'));
    }


    public function edit($calendar)
    {
        return view('calendar.edit', compact('calendar'));
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
        $calendar = EventCalendar::find($id);
        $calendar->name = $request->name;
        $calendar->color = $request->color;
        $calendar->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/calendar/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        EventCalendar::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('event/calendar/data');
    }

}
