<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoogleAccessToken extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'google_access_tokens';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token',
        'created',
        'expires_at',
        'expires_in',
        'json',
        'refresh_token',
        'token_type',
        'user_id',
    ];

    /**
     * Refresh the access token.
     *
     * @return void
     */
    public function refreshToken()
    {
        $token = (new Support\Google\AccessToken)
                    ->set($this->json)
                    ->refresh()
                    ->get();

        return $this->fill(['json' => $token])->save();
    }

    /**
     * Revoke the access token.
     *
     * @return void
     */
    public function revokeToken()
    {
        $revoke = (new Support\Google\AccessToken)
                    ->set($this->json)
                    ->revoke();

        return $this->delete();
    }

    /**
     * Calculate the expires at timestamp.
     *
     * @return string
     */
    public function setExpiresAtAttribute()
    {
        $this->attributes['expires_at'] = Carbon::createFromTimestampUTC($this->created + $this->expires_in)->toDateTimeString();
    }

    /**
     * Return the user the token belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
