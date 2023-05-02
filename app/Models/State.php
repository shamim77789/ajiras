<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = "states";

    public $timestamps = false;
    
	public function dioces()
    {
    	return $this->belongsTo('App\Models\Dioces','dioces_id','id');
    }
	
	public function user_states()
    {
    	return $this->belongsTo('App\Models\UserStates','state_id','id');
    }
}
