<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetMarket extends Model
{
       
        protected $fillable = ['servey_id','ref_table','target_market_name','target_market_value','updated_at','created_at'];
}
