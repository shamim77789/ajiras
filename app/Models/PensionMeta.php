<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PensionMeta extends Model
{
    protected $table = "pension_meta";
    public $timestamps = false;

    public function pension_template_meta()
    {
        return $this->hasOne(PensionTemplateMeta::class, 'id', 'pension_template_meta_id');
    }
}
