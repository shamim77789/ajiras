<?php

namespace App\Http\Controllers;

use App\Helpers\GeneralHelper;
use App\Models\Invoice;
use App\Models\Payroll;
use App\Models\Permission;
use App\Models\Repair;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Dioces;
use App\Models\State;
use App\Models\Branch;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Cartalyst\Sentinel\Roles\RoleInterface;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\eResponse
     */
    public function index()
    {
        if (!Sentinel::hasAccess('users')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $data = User::with('roles')->get();
        return view('user.data', compact('data'));
    }

    public function get_users(Request $request)
    {
        if (!Sentinel::hasAccess('users')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }

        $query = User::query();
        return DataTables::of($query)->editColumn('user', function ($data) {
            return $data->first_name . ' ' . $data->last_name;
        })->editColumn('action', function ($data) {
            $action = '<div class="btn-group"><button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-right" role="menu">';
            if (Sentinel::hasAccess('users.view')) {
                $action .= '<li><a href="' . url('user/' . $data->id . '/show') . '" class="">' . trans_choice('general.detail', 2) . '</a></li>';
            }
            if (Sentinel::hasAccess('users.update')) {
                $action .= '<li><a href="' . url('user/' . $data->id . '/edit') . '" class="">' . trans_choice('general.edit', 2) . '</a></li>';
            }
            $action .= "</ul></div>";
            return $action;
        })->editColumn('id', function ($data) {
            return '<a href="' . url('user/' . $data->id . '/show') . '">' . $data->id . '</a>';

        })->editColumn('name', function ($data) {
            return '<a href="' . url('user/' . $data->id . '/show') . '">' . $data->first_name . ' ' . $data->last_name . '</a>';

        })->editColumn('gender', function ($data) {
            if ($data->gender == "Male") {
                return trans_choice('general.male', 1);
            }
            if ($data->gender == "Female") {
                return trans_choice('general.female', 1);
            }

        })->rawColumns(['id', 'name', 'action'])->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('users.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $roles = DB::table('roles')->get();
        $role = array();
        foreach ($roles as $key) 
		{
            $role[$key->name] = $key->name;
        }
		$dioces = Dioces::all();
        return view('user.create', compact('role','dioces'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$data = $request->input();

        if (!Sentinel::hasAccess('users.create')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $rules = array(
            'email' => 'required|unique:users',
            'password' => 'required',
            'rpassword' => 'required|same:password',
            'first_name' => 'required',
            'last_name' => 'required',
        );
//        $validator = Validator::make(Input::all(), $rules);
  /*      if ($validator->fails()) {
            Flash::warning(trans('general.validation_error'));
            return redirect()->back()->withInput()->withErrors($validator);

        } else {
    */        $credentials = [
                'email' => $request->email,
                'password' => $request->password,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'notes' => $request->notes,
                'gender' => $request->gender,
                'phone' => $request->phone,
				'dioces' => $request->dioces,
            ];
            $user = Sentinel::registerAndActivate($credentials);
            $role = Sentinel::findRoleByName($request->role);
            $role->users()->attach($user->id);

			if(array_key_exists('state_province', $data))
			{
				foreach($data['state_province'] as $state)
				{
					DB::table('user_states')->insert([
														'user_id' => $user->id,
														'state_id' => $state,
														'created_at' => date('y-m-d H:i:s')
													]);	
				}
			}

			if(array_key_exists('branches', $data))
			{
				foreach($data['branches'] as $branch)
				{
					DB::table('user_branches')->insert([
														'user_id' => $user->id,
														'branch_id' => $branch,
														'created_at' => date('y-m-d H:i:s')
													]);	
				}
			}			
			GeneralHelper::audit_trail("Added user with id:".$user->id);
            Flash::success("Successfully Saved");
            return redirect('user/data');
     //   }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($user)
    {
        if (!Sentinel::hasAccess('users.view')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $payroll = Payroll::where('user_id', $user->id)->get();
        return view('user.show', compact('user','payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user)
    {
        if (!Sentinel::hasAccess('users.update')) 
		{
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $roles = DB::table('roles')->get();
        $role = array();
        foreach ($roles as $key) {
            $role[$key->name] = $key->name;
        }

        foreach ($user->roles as $sel) {
            $selected = $sel->name;
        }
		$dioces = Dioces::all();
		$user_states = DB::table('user_states')->where('user_id', $user->id)->select('state_id')->get();
		$user_branches = DB::table('user_branches')->where('user_id', $user->id)->select('branch_id')->get();
		$branches = array();
		$states = array();
		$state = '';
		$branch = '';
		$get_states = State::where('dioces_id', $user->dioces)->get();

		if(!empty($user_states))
		{
			foreach($user_states as $key => $state)
			{
				$states[] = $state->state_id; 
			}
		}
		if(!empty($user_branches))
		{
			foreach($user_branches as $key => $branch)
			{
				$branches[] = $branch->branch_id; 
			}
		}
		$state = '<label>State / Province</label>';
		$state .= '<div class="container">';
		$state .= '<div class="row">';	
			if(!empty($get_states))
			{
				foreach($get_states as $value)
				{ 
					$state .= '<div class="col-lg-3">';
					$state .= '<input type="checkbox" name="state_province[]" class="state_province" value='.$value->id.' '.(in_array($value->id,$states) ? 'checked' : '').'>';
					$state .= '<span style="margin-left:5px;">'.$value->name.'</span>';
					$state .= '</div>';
				}						
			}
			$state .= '<div class="col-lg-12 mt-5" style="margin-top:10px;">';
			$state .= '<button class="btn btn-primary mt-2 get_branches" type="button">Get Branches</button>';
			$state .= '</div>';
			$state .= '</div>';
			$state .= '</div>';		

			$abranches = array();
			$counter = 0;
			foreach($states as $key => $val)
			{
				$qbranch = Branch::where('state_id', $val)->get();
				if(!empty($qbranch))
				{
					foreach($qbranch as $index => $b)
					{
						$abranches[$counter]['id'] = $b->id;
						$abranches[$counter]['name'] = $b->name;
						$counter++;
					}
				}

			}
			
			$branch = '<label>Branches</label>';
			$branch .= '<div class="container">';
			$branch .= '<div class="row">';
			if(!empty($abranches))
			{
				foreach($abranches as $value)
				{
					$branch .= '<div class="col-lg-3">';
					$branch .= '<input type="checkbox" name="branches[]" class="branches" value="'.$value['id'].'" '.(in_array($value['id'],$branches) ? 'checked' : '').'>';
					$branch .= '<span style="margin-left:5px;">'.$value['name'].'</span>';
					$branch .= '</div>';
				}

			}
			$branch .= '</div>';
			$branch .= '</div>';		
		
		return view('user.edit', compact('user', 'role', 'selected','dioces','user_branches','user_states','state','branch'));
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
		$data = $request->input();
        if (!Sentinel::hasAccess('users.update')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $user = Sentinel::findById($id);
        $credentials = [
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'notes' => $request->notes,
            'gender' => $request->gender,
            'phone' => $request->phone,
			'dioces' => $request->dioces
        ];
        if (!empty($request->password)) {
            $credentials['password'] = $request->password;
        }
        if ($request->role != $request->previous_role) {

            $role = Sentinel::findRoleByName($request->previous_role);
            $role->users()->detach($user->id);
            $role = Sentinel::findRoleByName($request->role);
            $role->users()->attach($user->id);
        }
        $user = Sentinel::update($user, $credentials);
		DB::table('user_states')->where('user_id',$user->id)->delete();
		if(array_key_exists('state_province', $data))
		{
			foreach($data['state_province'] as $state)
			{
				DB::table('user_states')->insert([
					'user_id' => $user->id,
					'state_id' => $state,
					'created_at' => date('y-m-d H:i:s')
				]);	
			}
		}

		DB::table('user_branches')->where('user_id',$user->id)->delete();
		if(array_key_exists('branches', $data))
		{
			foreach($data['branches'] as $branch)
			{
				DB::table('user_branches')->insert([
					'user_id' => $user->id,
					'branch_id' => $branch,
					'created_at' => date('y-m-d H:i:s')
				]);	
			}
		}		
		
		
        GeneralHelper::audit_trail("Updated user with id:".$user->id);
        Flash::success("Successfully Saved");
        return redirect('user/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('users.delete')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $user = Sentinel::findById($id);
        $user->delete();
        GeneralHelper::audit_trail("Deleted user with id:".$id);
        Flash::success("Successfully Deleted");
        return redirect('user/data');
    }

    public function profile($id)
    {

        $user = Sentinel::findById($id);
        return view('user.profile', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request, $id)
    {
        if(Sentinel::getUser()->id!=$id){
            Flash::warning("Permission Denied");
            return redirect('dashboard');
        }
        $user = Sentinel::findById($id);
        $credentials = [
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'notes' => $request->notes,
            'gender' => $request->gender,
            'phone' => $request->phone
        ];
        if (!empty($request->password)) {
            $credentials['password'] = $request->password;
        }
        $user = Sentinel::update($user, $credentials);
        Flash::success("Successfully Saved");
        return redirect()->back();
    }

//manage permissions
    public function indexPermission()
    {
        $data = array();
        $permissions = Permission::where('parent_id', 0)->get();
        foreach ($permissions as $permission) {
            array_push($data, $permission);
            $subs = Permission::where('parent_id', $permission->id)->get();
            foreach ($subs as $sub) {
                array_push($data, $sub);
            }
        }
        return view('user.permission.data', compact('data'));
    }

    public function createPermission()
    {
        $parents = Permission::where('parent_id', 0)->get();
        $parent = array();
        $parent['0'] = "None";
        foreach ($parents as $key) 
		{
            $parent[$key->id] = $key->name;
        }

        return view('user.permission.create', compact('parent'));
    }

    public function storePermission(Request $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->parent_id = $request->parent_id;
        $permission->description = $request->description;
        if(!empty($request->slug)){
            $permission->slug = $request->slug;
        }else{
            $permission->slug = Str::slug($request->name, '_');
        }

        $permission->save();
        Flash::success("Successfully Saved");
        return redirect('user/permission/data');
    }

    public function editPermission($permission)
    {
        $parents = Permission::where('parent_id', 0)->get();
        $parent = array();
        $parent['0'] = "None";
        foreach ($parents as $key) {
            $parent[$key->id] = $key->name;
        }
        if ($permission->parent_id == 0) {
            $selected = 0;
        } else {
            $selected = 1;
        }

        return view('user.permission.edit', compact('parent', 'permission', 'selected'));
    }

    public function updatePermission(Request $request, $id)
    {
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->parent_id = $request->parent_id;
        $permission->description = $request->description;
        if(!empty($request->slug)){
            $permission->slug = $request->slug;
        }else{
            $permission->slug = Str::slug($request->name, '_');
        }
        $permission->save();
        Flash::success("Successfully Saved");
        return redirect('user/permission/data');
    }

//manage roles
    public function indexRole()
    {
        if (!Sentinel::hasAccess('users.roles')) {
            Flash::warning("Permission Denied");
            return redirect()->back();
        }
        $data = EloquentRole::all();
        return view('user.role.data', compact('data'));
    }

    public function createRole()
    {
        $data = array();
        $permissions = Permission::where('parent_id', 0)->get();
        foreach ($permissions as $permission) {
            array_push($data, $permission);
            $subs = Permission::where('parent_id', $permission->id)->get();
            foreach ($subs as $sub) {
                array_push($data, $sub);
            }
        }
        return view('user.role.create', compact('data'));
    }

    public function storeRole(Request $request)
    {
        $role = new EloquentRole();
        $role->name = $request->name;
        $role->slug = Str::slug($request->name, '_');
        $role->save();
        if(!empty($request->permission)) {
            foreach ($request->permission as $key) {
                $role->updatePermission($key, true, true)->save();
            }
        }
        GeneralHelper::audit_trail("Added role with id:".$role->id);
        Flash::success("Successfully Saved");
        return redirect('user/role/data');
    }

    public function editRole($id)
    {
        $data = array();
        $permissions = Permission::where('parent_id', 0)->get();
        foreach ($permissions as $permission) {
            array_push($data, $permission);
            $subs = Permission::where('parent_id', $permission->id)->get();
            foreach ($subs as $sub) {
                array_push($data, $sub);
            }
        }
        $role = EloquentRole::find($id);
        return view('user.role.edit', compact('data', 'role'));
    }

    public function updateRole(Request $request, $id)
    {
        //return print_r($request->permission);
        $role = Sentinel::findRoleById($id);
        $role->name = $request->name;
        $role->slug = Str::slug($request->name, '_');
        $role->permissions = array();
        $role->save();
        //remove permissions which have not been ticked
        //create and/or update permissions
        if(!empty($request->permission)) {
            foreach ($request->permission as $key) {
                $role->updatePermission($key, true, true)->save();
            }
        }

        GeneralHelper::audit_trail("Updated role with id:".$id);
        Flash::success("Successfully Saved");
        return redirect('user/role/data');
    }
    public function deletePermission($id){
        Permission::destroy($id);
        Flash::success("Successfully Saved");
        return redirect('user/permission/data');
    }
    public function deleteRole($id){
        EloquentRole::destroy($id);
        GeneralHelper::audit_trail("Deleted role with id:".$id);
        Flash::success("Successfully Saved");
        return redirect('user/role/data');
    }
	
	public function getstate(Request $request)
	{
		$dioces = $request->dioces;
		if($dioces == '-1')
			$get_states = State::all();
		else
			$get_states = State::where('dioces_id', $dioces)->get();
		$get_states = json_decode(json_encode($get_states), true);
		return $get_states;
	}
	
	public function branches(Request $request)
	{
		$branches = array();
		$dioces = $request->dioces;
		$counter = 0;
		foreach($dioces as $key => $dioce)
		{
			$branch = Branch::where('state_id', $dioce)->get();
			if(!empty($branch))
			{
				foreach($branch as $index => $b)
				{
					$branches[$counter]['id'] = $b->id;
					$branches[$counter]['name'] = $b->name;
					$counter++;
				}
			}

		}
	
		return $branches;
	
	}
	
	/*Validate Email*/
	public function validate_email(Request $request)
	{
		$email = $request->email;
		$user_validate = User::where('email', $email)->count();
		
		return $user_validate;
	}
	
}
