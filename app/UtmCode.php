<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UtmCode extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'utm_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * @see https://support.google.com/analytics/answer/1033867?hl=en
     */
    protected $fillable = [
        'ad_id',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_source',
        'utm_term',
    ];

    /**
     * Return the ad the UTM code belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad()
    {
        return $this->belongsTo( Ad::class );
    }

    /**
     * Automatically slug the utm_term.
     *
     * @param $utm_term
     *
     * @see https://support.google.com/analytics/answer/1033867?hl=en
     */
    public function setUtmTermAttribute( $utm_term )
    {
        $this->attributes['utm_term'] = Str::slug($utm_term, '+');
    }

    /**
     * Return the user that created the record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
