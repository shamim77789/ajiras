<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table = "campaigns";

    public function pledges()
    {
        return $this->hasMany(Pledge::class, 'campaign_id', 'id')->orderBy('created_at', 'desc');
    }
}
