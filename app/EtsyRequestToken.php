<?php

namespace App;

use App\Support\Etsy\Etsy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtsyRequestToken extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'etsy_request_tokens';

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
        'oauth_token_secret',
        'oauth_token',
        'oauth_verifier',
        'user_id',
    ];

    /**
     * Connect with the Etsy API.
     *
     * @return Etsy
     */
    public function connectWithEtsyApi()
    {
        return new Etsy($this->oauth_token, $this->oauth_token_secret);
    }

    /**
     * Return the user the request token belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
