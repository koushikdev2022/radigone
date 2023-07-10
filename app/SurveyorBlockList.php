<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyorBlockList extends Model
{
    protected $fillable = [
        'surveyor_id',
        'user_id',
    ];

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class, 'surveyor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
