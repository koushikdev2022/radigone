<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 
    protected $table = 'agents';
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'ver_code_send_at' => 'datetime'
    ];

    protected $data = [
        'data'=>1
    ];




    // public function login_logs()
    // {
    //     return $this->hasMany(SurveyorLogin::class);
    // }

    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class)->orderBy('id','desc');
    // }

    // public function deposits()
    // {
    //     return $this->hasMany(Deposit::class)->where('status','!=',0);
    // }

    // public function withdrawals()
    // {
    //     return $this->hasMany(Withdrawal::class)->where('status','!=',0);
    // }


    // SCOPES

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

    // public function surveys()
    // {
    //     return $this->hasMany(Survey::class);
    // }
}
