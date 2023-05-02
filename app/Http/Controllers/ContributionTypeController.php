<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\ContributionBatch;
use App\Models\ContributionType;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use App\Models\Deduction;
use App\Models\contributionDeduction;
use App\Models\ContributionTypeEstimation;
use App\Models\Fund;
use DB;
class ContributionTypeController extends Controller
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
        if (!Sentinel::hasAccess('contributions')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $data = ContributionType::all();

        return view('contribution_type.data', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       if (!Sentinel::hasAccess('contributions.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $deductions = Deduction::get();
		$funds = Fund::get();
        //get custom fields
        return view('contribution_type.create')
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
        if (!Sentinel::hasAccess('contributions.create')) 
		{
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $deductions = $request->input('deductions');

        $contribution_type = new ContributionType();
        $contribution_type->name = $request->name;
		$contribution_type->fund = $request->fund;

        if($contribution_type->save())
		{
            foreach ($deductions as $key => $value) {
                $contribution_deduction = new contributionDeduction;
                $contribution_deduction->contribution_type_id = $contribution_type->id;
                $contribution_deduction->deduction_id = $value;
                $contribution_deduction->save();
            }

        }
		
		$contribution_type_estimation = new ContributionTypeEstimation();
		if(!empty($request->name))
		{
			$estimation_amount = $request->estimation_amount;
			foreach($estimation_amount as $key => $value)
			{
				DB::table('contribution_types_estimation')
				->insert([
							'contribution_type_id' => $contribution_type->id,
							'estimation_amount' => $request->estimation_amount[$key],
							'year' => $request->year[$key],
							'quarter' => $request->quarter[$key]
						 ]);
				
			}
		}
		
        Flash::success(trans('general.successfully_saved'));
        return redirect('contribution/type/data');
    }


    public function show($contribution_type)
    {
        if (!Sentinel::hasAccess('contributions.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        return view('contribution_type.show', compact('contribution_type'));
    }


    public function edit($id)
    {
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $contribution_type = (int)$id;

        $deductions = Deduction::get();

		$funds = Fund::get();

        $contributiontype = ContributionType::where('id', $contribution_type)
            ->first();
		
        $cdeduction = contributionDeduction::where('contribution_type_id', $contribution_type)
            ->pluck('deduction_id')
            ->toArray();

		$contribution_type_estimation = ContributionTypeEstimation::where('contribution_type_id', $id)->get();

        return view('contribution_type.edit', compact('contributiontype', 'cdeduction', 'deductions','contribution_type_estimation','funds'));
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
        if (!Sentinel::hasAccess('contributions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $deductions = $request->input('deductions');

        $id = (int)$id;

        $contribution_type = ContributionType::find($id);
        $contribution_type->name = $request->input('name');
		$contribution_type->fund = $request->fund;
//		$contribution_type->year = $request->year;
//		$contribution_type->estimation_amount = $request->estimation_amount;
//		$contribution_type->quarter = $request->quarter;
		if($contribution_type->save()){

            contributionDeduction::where('contribution_type_id', $id)->delete();

            foreach ($deductions as $key => $value) {
                $contribution_deduction = new contributionDeduction;
                $contribution_deduction->contribution_type_id = $id;
                $contribution_deduction->deduction_id = $value;
                $contribution_deduction->save();
            }

        }
		ContributionTypeEstimation::where('contribution_type_id', $id)->delete();
	
		if($contribution_type->save())
		{
			$contribution_type_estimation = new ContributionTypeEstimation();
			$estimation_amount = $request['estimation_amount'];
			
			foreach($estimation_amount as $key => $value)
			{
				DB::table('contribution_types_estimation')
				->insert([
							'contribution_type_id' => $contribution_type->id,
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
        return redirect('contribution/type/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('contributions.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        ContributionType::destroy($id);
        contributionDeduction::where('contribution_type_id', $id)->delete();
        Flash::success(trans('general.successfully_deleted'));
        return redirect('contribution/type/data');
    }

}
