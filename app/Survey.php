<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'users' => 'array',
        'country' => 'array'
    ];

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function adtype()
    {
        return $this->belongsTo(AdType::class, 'ad_type', 'id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    public function targetMarket()
    {
        return $this->hasOne(TargetMarket::class,'servey_id','id');
    }
}
