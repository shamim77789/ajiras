<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Models\VolunteerRole;
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

class VolunteerRoleController extends Controller
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
        $data = VolunteerRole::all();
        return view('volunteer_role.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //get custom fields
        return view('volunteer_role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new VolunteerRole();
        $role->name = $request->name;
        $role->notes = $request->notes;
        $role->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/role/data');
    }


    public function show($volunteer_role)
    {

        return view('volunteer_role.show', compact('volunteer_role'));
    }


    public function edit($volunteer_role)
    {
        return view('volunteer_role.edit', compact('volunteer_role'));
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
        $role = VolunteerRole::find($id);
        $role->name = $request->name;
        $role->notes = $request->notes;
        $role->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/role/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        VolunteerRole::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('event/role/data');
    }

}
