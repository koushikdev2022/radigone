<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class campaign extends Model
{
    protected $guarded = ['id'];
    protected $fillable = [
        'p_name',
        'p_specification',
        'p_mrp',
        'discount',
        'required_data',
        'offer_type',
        'total_views',
        'publish',
        'target_market_category',
        'total_slides',
        'slides_time',
        'repeated_viewers',
        'ad_duration',
        'online_purchase',
        'template',
    ];

    protected $primaryKey = 'contractId';
}
