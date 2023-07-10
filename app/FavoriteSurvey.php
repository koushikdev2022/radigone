<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteSurvey extends Model
{
    protected $fillable = [
        'survey_id',
        'user_id',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
