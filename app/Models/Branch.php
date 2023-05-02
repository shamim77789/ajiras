<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = "branches";

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(BranchUser::class, 'branch_id', 'id');
    }
    public function dioces()
    {
    	return $this->belongsTo('App\Models\Dioces','dioces_id','id');
    }
    public function state()
    {
    	return $this->belongsTo('App\Models\State','state_id','id');
    }

	public function user_branches()
	{
		return $this->belongsTo('App\Models\UserBranches','branch_id','id');
	}
	
}
