<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStates extends Model
{
	protected $table = 'user_states';
	protected $guarded = [];
	
	public function states()
	{
		$this->belongsTo('App\Models\States','state_id','id');
	}
	
}
