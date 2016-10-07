<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Metric extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'metrics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'clicks',
        'cost',
        'cpc',
        'cpi',
        'cpt',
        'ctr',
        'ecommerce_conversion_rate',
        'fb_comments',
        'fb_conversion_rate',
        'fb_likes',
        'fb_shares',
        'frequency',
        'impressions',
        'per_click_value',
        'reach',
        'revenue',
        'roi',
        'spend',
        'transactions'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Return the ad that the metric belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo( Ad::class );
    }
}