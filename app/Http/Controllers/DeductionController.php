<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Deduction;
use App\Models\Branch;
use Auth;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Flash\Flash;

class DeductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel');

    }

    public function index() {

    	if (!Sentinel::hasAccess('deductions')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

    	$branches = Branch::get();

    	return view('deduction.index')
    		->with('branches', $branches);
    }

    public function branch_deductions(Request $request){
    	$id = $request->get('id');

    	$deductions = Deduction::where('branch_id', $id)
    		->get();
    	echo '<table id="data-table" class="table table-bordered table-condensed table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Deduction Type</th>
                        <th>Amount Type</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>';
        $count = 0;
    	foreach ($deductions as $key => $value) {
    		$count = $count + 1;
    		?>
    		<tr>
    			<td><?php echo $count; ?></td>
    			<td><?php echo $value->deduction_type; ?></td>
    			<td><?php echo ($value->amount_type == '1' ? 'Fix' : 'Percentage');  ?></td>
    			<td><?php echo $value->amount; ?></td>
    			<td>
    				<span style="padding-right: 7%">
    					<a href="edit/<?php echo $value->id; ?>" style="color:green;"><i class="fa fa-edit"></i></a>
    				</span>
    				<span>
    					<a href="delete/<?php echo $value->id; ?>" style="color:red;"><i class="fa fa-trash"></i></a>
    				</span>
    			</td>
    		</tr>
    		<?php
    	}
    	echo '</tbody>
                </table>';
    }

    public function create(){

    	if (!Sentinel::hasAccess('deductions.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

    	$branches = Branch::get();

    	return view('deduction.create')
    		->with('branches', $branches);

    }

     public function store(Request $request)
    {
        if (!Sentinel::hasAccess('deductions.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $check = Deduction::where('branch_id', $request->input('branch'))
        	->where('deduction_type', $request->input('deduction_type'))
        	->where('amount', $request->input('amount'))
        	->first();

        if($check){

        	Flash::warning("Deduction already exists!");

        }else{

        	$deduction = new Deduction();
	        $deduction->branch_id = $request->input('branch');
	        $deduction->deduction_type = $request->input('deduction_type');
	        $deduction->amount_type = $request->input('amount_type');
	        $deduction->amount = $request->input('amount');
	        $deduction->user_id = Sentinel::getUser()->id;
	        $deduction->save();
	        Flash::success("Deduction saved successfully!");

        }

        return redirect()->back();
    }

     public function edit($id)
    {
        if (!Sentinel::hasAccess('deductions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $deduction = Deduction::where('id', $id)
        	->first();

        $branches = Branch::get();

        return view('deduction.edit', compact('deduction', 'branches'));
    }


    public function update(Request $request)
    {
        if (!Sentinel::hasAccess('deductions.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        // $deduction = Deduction::find($request->input('id'));
        // $deduction->branch_id = $request->input('branch_id');
        // $deduction->deduction_type = $request->input('deduction_type');
        // $deduction->amount = $request->input('amount');
        // $deduction->user_id = Sentinel::getUser()->id;
        // $deduction->save();

        Deduction::where('id', $request->input('id'))
        	->update([
        		'branch_id' => $request->input('branch_id'),
        		'deduction_type' => $request->input('deduction_type'),
        		'amount' => $request->input('amount'),
				'amount_type' => $request->input('amount_type'),
        		'user_id' => Sentinel::getUser()->id
        	]);

        Flash::success("Deduction updated successfully!");
        return redirect()->route('deduction.show');
    }


    public function delete($id)
    {
        if (!Sentinel::hasAccess('deductions.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        Deduction::destroy($id);
        Flash::success("Deduction deleted successfully!");
        return redirect()->back();
    }
}
