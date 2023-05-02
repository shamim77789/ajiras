<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use App\Models\Deduction;
use App\Models\PledgeDeduction;
use App\Models\PledgeType;
use App\Models\PledgeTypeEstimation;
use App\Models\Fund;
use App\Models\Pledge;
use DB;
class PledgeTypeController extends Controller
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
        if (!Sentinel::hasAccess('pledges_type')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $data = PledgeType::all();

        return view('pledges_type.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('pledges_type.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $deductions = Deduction::get();
		$funds = Fund::get();
		//get custom fields
        return view('pledges_type.create')
            ->with(compact('deductions','funds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$dNow = date('y-m-d H:i:s');
        if (!Sentinel::hasAccess('pledges_type.create')) 
		{
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $deductions = $request->input('deductions');

        $pledge_type = new PledgeType();
        $pledge_type->name = $request->name;
		$pledge_type->fund = $request->fund;
        if($pledge_type->save())
		{
            foreach ($deductions as $key => $value) {
                $pledge_deduction = new PledgeDeduction;
                $pledge_deduction->pledge_type_id = $pledge_type->id;
                $pledge_deduction->deduction_id = $value;
                $pledge_deduction->save();
            }

        }
		
		$pledge_type_estimation = new PledgeTypeEstimation();
		if(!empty($request->name))
		{
			$estimation_amount = $request->estimation_amount;
			foreach($estimation_amount as $key => $value)
			{
				DB::table('pledges_types_estimation')
				->insert([
							'pledge_type_id' => $pledge_type->id,
							'estimation_amount' => $request->estimation_amount[$key],
							'year' => $request->year[$key],
							'quarter' => $request->quarter[$key]
						 ]);
			}
		}
		
        Flash::success(trans('general.successfully_saved'));
        return redirect('pledge/campaign_type/data');
    }


    public function show($contribution_type)
    {
        if (!Sentinel::hasAccess('pledges_type.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        return view('pledges_type.show', compact('pledge_type'));
    }


    public function edit($id)
    {
        if (!Sentinel::hasAccess('pledges_type.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $pledge_type = (int)$id;

        $deductions = Deduction::get();
		$funds = Fund::get();

        $pledgetype = PledgeType::where('id', $pledge_type)
            ->first();

        $cdeduction = PledgeDeduction::where('pledge_type_id', $pledge_type)
            ->pluck('deduction_id')
            ->toArray();

		$pledge_type_estimation = PledgeTypeEstimation::where('pledge_type_id', $id)->get();

        return view('pledges_type.edit', compact('pledgetype', 'cdeduction', 'deductions','pledge_type_estimation','funds'));
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
		$dNow = date('y-m-d H:i:s');
        if (!Sentinel::hasAccess('pledges_type.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $deductions = $request->input('deductions');

        $id = (int)$id;

        $pledge_type = PledgeType::find($id);
        $pledge_type->name = $request->input('name');
		$pledge_type->fund = $request->fund;
		if($pledge_type->save()){

            PledgeDeduction::where('pledge_type_id', $id)->delete();

            foreach ($deductions as $key => $value) {
                $pledge_deduction = new PledgeDeduction;
                $pledge_deduction->pledge_type_id = $id;
                $pledge_deduction->deduction_id = $value;
                $pledge_deduction->save();
            }

        }
		PledgeTypeEstimation::where('pledge_type_id', $id)->delete();
	
		if($pledge_type->save())
		{
			$pledge_type_estimation = new PledgeTypeEstimation();
			$estimation_amount = $request['estimation_amount'];
			
			foreach($estimation_amount as $key => $value)
			{
				DB::table('pledges_types_estimation')
				->insert([
							'pledge_type_id' => $pledge_type->id,
							'estimation_amount' => $request->estimation_amount[$key],
							'year' => $request->year[$key],
							'quarter' => $request->quarter[$key]
						 ]);
/*				
				$contribution_type_estimation->contribution_type_id = $id;
				$contribution_type_estimation->estimation_amount = $request['estimation_amount'][$key];
				$contribution_type_estimation->year = $request['year'][$key];
				$contribution_type_estimation->quarter = $request['quarter'][$key];
				$contribution_type_estimation->save();			

*/
			}
			
		}
		
		Flash::success(trans('general.successfully_saved'));
        return redirect('pledge/campaign_type/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('pledges_type.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        PledgeType::destroy($id);
        PledgeDeduction::where('pledge_type_id', $id)->delete();
        Flash::success(trans('general.successfully_deleted'));
        return redirect('pledge/campaign_type/data');
    }

}
