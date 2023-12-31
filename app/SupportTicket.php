<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $guarded = ['id'];

    public function getUsernameAttribute()
    {
        return $this->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveyor()
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function supportMessage(){
        return $this->hasMany(SupportMessage::class, 'supportticket_id');
    }

}
