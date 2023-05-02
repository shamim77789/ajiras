<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBranches extends Model
{
    protected $table = "user_branches";
	protected $guarded = [];
}
