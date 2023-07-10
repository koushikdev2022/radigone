<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }
}
