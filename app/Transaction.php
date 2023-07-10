<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transactions";

    protected  $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

}
