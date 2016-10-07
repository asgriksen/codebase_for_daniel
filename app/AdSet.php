<?php

namespace App;

use App\Support\Model\Traits\ApplyActionTrait;
use App\Support\Model\Traits\CheckRuleConditionTrait;
use App\Support\Model\Traits\Enums;
use App\Support\Model\Traits\Metrics;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AdSet extends Model
{
    use ApplyActionTrait,
        CheckRuleConditionTrait,
        Enums,
        Metrics,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ad_sets';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['fb_created_at', 'fb_updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'budget_remaining',
        'lifetime_budget',
        'daily_budget',
        'fb_adset_id',
        'fb_campaign_id',
        'fb_created_at',
        'fb_updated_at',
        'name',
        'prefix',
        'status',
        'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    const TYPE_GET_VISITORS_ADS    = 'GET_VISITORS_ADS';
    const TYPE_BOOST_POST_ADS      = 'BOOST_POST_ADS';
    const TYPE_DYNAMIC_PRODUCT_ADS = 'DYNAMIC_PRODUCT_ADS';

    const STATUS_ACTIVE   = 'ACTIVE';
    const STATUS_PAUSED   = 'PAUSED';
    const STATUS_ARCHIVED = 'ARCHIVED';
    const STATUS_DELETED  = 'DELETED';

    /**
     * Access the Facebook account of ad set's campaign account.
     *
     * @return mixed
     */
    public function accessFacebook()
    {
        return $this->campaign->accessFacebook();
    }

    /**
     * Access the ad set data transfer object.
     *
     * @return App\Support\FB\FBAdSet
     */
    public function getDataTransferObject()
    {
        return $this->accessFacebook()->getAdSet( $this->fb_adset_id );
    }

    /**
     * Return all of the ad set's ads.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany( Ad::class );
    }

    /**
     * Return the campaign that the ad set belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo( Campaign::class );
    }

    /**
     * Return the fields that are specific to Facebook.
     *
     * @return array
     */
    public function getFacebookIdFields()
    {
        return array_only($this->toArray(), ['fb_adset_id']);
    }

    /**
     * Define accessor that will fetch the ad set metric
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

        // Obtain the facebook ad set it.
        //
        $fb_adset_id = $this->fb_adset_id;

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("{$fb_adset_id} {$start_date} {$end_date} {$time_increment}");

        // Assign account to a variable so that we can inject it
        // into the cache closure.
        //
        $account = $this->campaign->account;

        // How many minutes do we want the cache to remember this information?
        //
        $minutes_to_remember = 1;

        // Define the closure that will be used by the cache facade.
        //
        $closure = function() use($fb_adset_id, $account, $fields, $start_date, $end_date, $time_increment)
        {
            return $account
                ->accessFacebook()
                ->getAdSet( $fb_adset_id )
                ->getInsightsBetweenDates( $fields, $start_date, $end_date, $time_increment );
        };

        return Cache::remember($identifier, $minutes_to_remember, $closure);
    }

    /**
     * Return all rules of the ad set.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rules()
    {
        return $this->morphMany( Rule::class, 'control');
    }

    /**
     * Return all of the ad set's rule applications.
     *
     * @return mixed
     */
    public function rule_applications()
    {
        return $this->hasMany(RuleApplication::class, 'ref_id')
                    ->where('layer', '=', App\RuleApplication::LAYER_AD_SET);
    }

    /**
     * Automatically format the timestamp.
     *
     * @param $timestamp
     */
    public function setFbCreatedAtAttribute( $timestamp )
    {
        $this->attributes['fb_created_at'] = $timestamp ? Carbon::parse($timestamp)->toDateTimeString() : '0000-00-00 00:00:00';
    }

    /**
     * Automatically format the timestamp.
     *
     * @param $timestamp
     */
    public function setFbUpdatedAtAttribute( $timestamp )
    {
        $this->attributes['fb_updated_at'] = $timestamp ? Carbon::parse($timestamp)->toDateTimeString() : '0000-00-00 00:00:00';
    }
}
