<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTemp extends Model
{
    protected $table = "transaction_temps";

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
