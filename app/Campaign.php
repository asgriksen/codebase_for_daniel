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

class Campaign extends Model
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
    protected $table = 'campaigns';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['fb_created_at', 'fb_updated_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'bidding',
        'campaign_end',
        'conversion_pixel',
        'fb_campaign_id',
        'fb_created_at',
        'fb_updated_at',
        'name',
        'optimize_for',
        'schedule',
        'schedule_type',
        'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    const SCHEDULE_TYPE_CONTINUE     = 'CONTINUE';
    const SCHEDULE_TYPE_START_END    = 'START_END';
    const SCHEDULE_TYPE_DAYS_OF_WEEK = 'DAYS_OF_WEEK';

    const OPTIMIZE_FOR_CLICKS_TO_WEBSITE  = 'LINK_CLICKS';
    const OPTIMIZE_FOR_CLICKS             = 'CLICKS';
    const OPTIMIZE_FOR_DAILY_UNIQUE_REACH = 'REACH';
    const OPTIMIZE_FOR_IMPRESSIONS        = 'IMPRESSIONS';

    const CAMPAIGN_END_PAUSE   = 'PAUSE';
    const CAMPAIGN_END_DELETE  = 'DELETE';
    const CAMPAIGN_END_NOTHING = 'NOTHING';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';
    const STATUS_ARCHIVED = 'ARCHIVED';
    const STATUS_DELETED = 'DELETED';

    /**
     * Return the Facebook Api for the campaign account holder.
     *
     * @return App\Support\FB\FB|null
     */
    public function accessFacebook()
    {
        return $this->account->accessFacebook();
    }

    /**
     * Access the ad campaign data transfer object.
     *
     * @return App\Support\FB\FBAdCampaign
     */
    public function getDataTransferObject()
    {
        return $this->accessFacebook()->getAdCampaign( $this->fb_campaign_id );
    }

    /**
     * Return the account the campaign belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo( Account::class );
    }

    /**
     * Return all of the campaign's ad sets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adSets()
    {
        return $this->hasMany( AdSet::class );
    }

    /**
     * Return the fields that are specific to Facebook.
     *
     * @return array
     */
    public function getFacebookIdFields()
    {
        return array_only($this->toArray(), ['fb_campaign_id']);
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
        $account = $this->account;

        // How many minutes do we want the cache to remember this information?
        //
        $minutes_to_remember = 1;

        // Define the closure that will be used by the cache facade.
        //
        $closure = function() use($account, $fields, $start_date, $end_date, $time_increment)
        {
            return $account
                ->accessFacebook()
                ->getAdCampaign( $this->fb_campaign_id )
                ->getInsightsBetweenDates( $fields, $start_date, $end_date, $time_increment );
        };

        return Cache::remember($identifier, $minutes_to_remember, $closure);
    }

    /**
     * Return all rules of the campaign.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rules()
    {
        return $this->morphMany( Rule::class, 'control');
    }

    /**
     * Return all of the campaign's rule applications.
     *
     * @return mixed
     */
    public function rule_applications()
    {
        return $this->hasMany( RuleApplication::class , 'ref_id')
                    ->where('layer', '=', App\RuleApplication::LAYER_CAMPAIGN );
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