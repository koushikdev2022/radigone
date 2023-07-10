<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategoryRequest extends Model
{
    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class, 'surveyor_id');
    }
}
