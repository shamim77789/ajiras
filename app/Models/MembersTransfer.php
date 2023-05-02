<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembersTransfer extends Model
{
    protected $table = "members_transfer";
	protected $guarded = [];

	public function member()
    {
        return $this->hasOne('App\Models\Member', 'id', 'member_id');
    }	

	public function member_from_branch()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'transfer_from_branch');
    }	

	public function member_to_branch()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'transfer_to_branch');
    }	
	

	public function dioce()
    {
        return $this->hasOne('App\Models\Dioces', 'id', 'dioces');
    }		
	
	
}
