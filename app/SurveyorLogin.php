<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyorLogin extends Model
{
    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }
}
