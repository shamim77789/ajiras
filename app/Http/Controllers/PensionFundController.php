<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use App\Models\PensionFund;
use App\Models\ExpenseType;
class PensionFundController extends Controller
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
        $data = PensionFund::all();
		$expense_type = ExpenseType::all();
        return view('pension_fund.data', compact('data','expense_type'));
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
		$pension_fund_id = $request->pension_fund_id;
		if($pension_fund_id == '0')
	        $pension_fund = new PensionFund();
		else
    	    $pension_fund = PensionFund::find($pension_fund_id);
		
        $pension_fund->name=$request->name;
        $pension_fund->amount=$request->amount;
        $pension_fund->type=$request->type;
		$pension_fund->employer_contribution = $request->employer_contribution;
		$pension_fund->employer_type = $request->employer_type;
		$pension_fund->expense_type = $request->expense_type;
		$pension_fund->save();
        Flash::success("Successfully Saved");
        return redirect('pension_fund/data');
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
		$pension_fund->employer_type = $request->employer_type;
		$pension_fund->expense_type = $request->expense_type;
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
        PensionFund::destroy($id);
        Flash::success("Successfully Deleted");
        return redirect('pension_fund/data');
    }
}
