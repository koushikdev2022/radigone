<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewSurvey extends Model
{
    protected $fillable = [
        'survey_id',
        'user_id',
        'is_repeated',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }
}
