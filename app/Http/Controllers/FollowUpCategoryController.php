<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Models\FollowUpCategory;
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

class FollowUpCategoryController extends Controller
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
        $data = FollowUpCategory::all();
        return view('follow_up_category.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //get custom fields
        return view('follow_up_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new FollowUpCategory();
        $category->name = $request->name;
        $category->notes = $request->notes;
        $category->days = $request->days;
        $category->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('follow_up/category/data');
    }


    public function show($follow_up_category)
    {

        return view('follow_up_category.show', compact('follow_up_category'));
    }


    public function edit($follow_up_category)
    {
        return view('follow_up_category.edit', compact('follow_up_category'));
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
        $category = FollowUpCategory::find($id);
        $category->name = $request->name;
        $category->notes = $request->notes;
        $category->days = $request->days;
        $category->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('follow_up/category/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        FollowUpCategory::destroy($id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('follow_up/category/data');
    }

}
