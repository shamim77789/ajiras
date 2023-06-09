<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use App\Models\Tax;

class TaxController extends Controller
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
        $data = Tax::all();
        return view('tax.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tax.create', compact(''));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		$tax_id = $request->tax_id;
		if($tax_id == '0')
	        $tax=new Tax();
		else
    	    $tax=Tax::find($tax_id);
		
        $tax->name=$request->name;
        $tax->amount=$request->amount;
        $tax->type=$request->type;
        $tax->save();
        Flash::success("Successfully Saved");
        return redirect('tax/data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($tax)
    {
        return View::make('tax.edit', compact('tax'))->render();
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
        $tax=Tax::find($id);
        $tax->title=$request->name;
        $tax->amount=$request->amount;
        $tax->type=$request->type;
        $tax->save();
        Flash::success("Successfully Saved");
        return redirect('tax/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tax::destroy($id);
        Flash::success("Successfully Deleted");
        return redirect('tax/data');
    }
}
