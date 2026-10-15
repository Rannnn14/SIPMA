<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $guarded = [];

    public function violationCategory()
    {
        return $this->belongsTo(ViolationCategory::class);
    }
}
