<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionBatch extends Model
{
    protected $table = "contribution_batches";

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'contribution_batch_id', 'id')->orderBy('date', 'desc');
    }

}
