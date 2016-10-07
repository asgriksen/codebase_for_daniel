<?php

namespace App;

use App\Support\FacebookTokenManager;
use App\Support\FB\FB;
use App\Support\Model\Traits\Metrics;
use Cache;
use FacebookAds\Api;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Account extends Model
{
    use Metrics,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fb_account_id',
        'fb_token',
        'fb_token_expiry',
        'is_selected',
        'name',
        'revenue',
        'roi',
        'transactions',
        'user_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Access the account's Facebook API data.
     *
     * @return Api|null
     */
    public function accessFacebook()
    {
        return new FB($this);
    }

    /**
     * Return all ad sets where campaigns are parented by the account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function adSets()
    {
        return $this->hasManyThrough( AdSet::class, Campaign::class );
    }

    /**
     * Return all account campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany( Campaign::class );
    }

    /**
     * Return the compiled CPA value.
     *
     * @return int
     */
    public function getCpaAttribute()
    {
        $cpa = 0;

        foreach( $this->metrics as $metric )
        {
            $cpa = $cpa + $metric['cost_per_total_action'];
        }

        return $cpa;
    }

    /**
     * Define accessor that will fetch the campaign metric
     * data from the Facebook ads api.
     *
     * @return array
     */
    public function getMetricsAttribute()
    {
        $fields         = $this->metric_fields;
        $start_date     = $this->metric_start_date;
        $end_date       = $this->metric_end_date;
        $time_increment = $this->metric_time_increment;

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("{$this->fb_campaign_id} {$start_date} {$end_date} {$time_increment}");

        // Assign account to a variable so that we can inject it
        // into the cache closure.
        //
        $account = $this;

        // How many minutes do we want the cache to remember this information?
        //
        $minutes_to_remember = 0; //1;

        // Define the closure that will be used by the cache facade.
        //
        $closure = function() use($account, $fields, $start_date, $end_date, $time_increment)
        {
            return $account
                ->accessFacebook()
                ->getAdAccount()
                ->getInsightsBetweenDates( $fields, $start_date, $end_date, $time_increment );
        };

        return Cache::remember($identifier, $minutes_to_remember, $closure);
    }

    /**
     * Is the user the owner of the account record.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isOwnedByUser( User $user )
    {
        return $this->user_id == $user->id;
    }

    /**
     * Query scope that will fetch records where is_selected is true.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsSelected( $query )
    {
        return $query->whereIsSelected( TRUE );
    }

    /**
     * Query scope that will fetch records where is_selected is false.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeIsNotSelected( $query )
    {
        return $query->whereIsSelected( FALSE );
    }

    /**
     * Return the user that the account belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo( User::class );
    }
}
