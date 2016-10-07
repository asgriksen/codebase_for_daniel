<?php

namespace App;

use App\Support\Model\Traits\ApplyActionTrait;
use App\Support\Model\Traits\Enums;
use App\Support\Model\Traits\Metrics;
use App\Support\Model\Traits\CheckRuleConditionTrait;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ad extends Model
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
    protected $table = 'ads';

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
        'action_type',
        'bid',
        'budget',
        'description',
        'distribute',
        'fb_ad_id',
        'fb_created_at',
        'fb_fan_page_id',
        'fb_updated_at',
        'facebook_fanpage_link',
        'facebook_post_link',
        'facebook_ad_set_link',
        'name',
        'photo',
        'status',
        'target_desktop',
        'target_mobile',
        'type',
        'url',
        'viral_style_campaign_admin_link',
        'viral_style_product_link'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    const TYPE_NEWS_FEED_AD         = 'NEWS_FEED_AD';
    const TYPE_RIGHT_HAND_SIDE_AD   = 'RIGHT_HAND_SIDE_AD';
    const TYPE_MULTIPLE_PRODUCTS_AD = 'MULTIPLE_PRODUCTS_AD';

    const STATUS_ACTIVE   = 'ACTIVE';
    const STATUS_PAUSED   = 'PAUSED';
    const STATUS_ARCHIVED = 'ARCHIVED';
    const STATUS_DELETED  = 'DELETED';

    const DISTRIBUTE_IMAGE      = 'IMAGE';
    const DISTRIBUTE_TITLE       = 'TITLE';
    const DISTRIBUTE_DESCRIPTION = 'DESCRIPTION';
    const DISTRIBUTE_URL         = 'URL';

    const ACTION_TYPE_SHOP_NOW   = 'SHOP_NOW';
    const ACTION_TYPE_LEARN_MORE = 'LEARN_MORE';
    const ACTION_TYPE_SIGN_UP    = 'SIGN_UP';
    const ACTION_TYPE_BOOK_NOW   = 'BOOK_NOW';
    const ACTION_TYPE_DOWNLOAD   = 'DOWNLOAD';

    /**
     * Access the Facebook account of ad's ad set account.
     *
     * @return mixed
     */
    public function accessFacebook()
    {
        return $this->ad_set->accessFacebook();
    }

    /**
     * Access the ad group data transfer object.
     *
     * @return App\Support\FB\FBAdGroup
     */
    public function getDataTransferObject()
    {
        return $this->accessFacebook()->getAdGroup( $this->fb_ad_id );
    }

    /**
     * Return the fields that are specific to Facebook.
     *
     * @return array
     */
    public function getFacebookIdFields()
    {
        return array_only($this->toArray(), ['fb_ad_id']);
    }

    /**
     * Return the products of the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany( Product::class );
    }

    /**
     * Return all ad metrics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function metrics()
    {
        return $this->hasMany( Metric::class );
    }

    /**
     * Return the ad set the ad belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ad_set()
    {
        return $this->belongsTo( AdSet::class );
    }

    /**
     * Define accessor that will fetch the age statistics.
     *
     * @return array
     */
    public function getAgeStatisticsAttribute()
    {
        $parameters = [
            'account'        => $this->ad_set->campaign->account,
            'breakdowns'     => 'age',
            'fields'         => 'age,spend,cpm,ctr,cpc,frequency',
            'start_date'     => isSet($this->metric_start_date) ? $this->metric_start_date : '',
            'end_date'       => isSet($this->metric_end_date) ? $this->metric_end_date : '',
            'id'             => $this->fb_ad_id,
        ];

        if( isSet($this->metric_time_increment) && $this->metric_time_increment )
        {
            $parameters['time_increment'] = $this->metric_time_increment;
        }

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("age-statistics-" . implode('-', $parameters));

        // Define the closure that will be injected into the cache facade.
        //
        $closure = function() use( $parameters )
        {
            $query_string_fields = array_only($parameters, ['breakdowns', 'fields', 'start_date', 'end_date', 'time_increment']);
            $query_string = http_build_query($query_string_fields);

            $fb = connect_to_facebook();

            $results = $fb->get('/'.$parameters['id'].'/insights?' . $query_string, $parameters['account']->fb_token);

            $results = json_decode($results->getBody(), true);

            $results = array_only($results, ['data']);

            return isSet($results['data']) ? $results['data'] : $results;
        };

        return Cache::remember($identifier, 1, $closure);
    }

    /**
     * Define accessor that will fetch the gender statistics.
     *
     * @return array
     */
    public function getGenderStatisticsAttribute()
    {
        $parameters = [
            'account'        => $this->ad_set->campaign->account,
            'breakdowns'     => 'gender',
            'fields'         => 'gender,spend,cpm,ctr,cpc,frequency',
            'start_date'     => isSet($this->metric_start_date) ? $this->metric_start_date : '',
            'end_date'       => isSet($this->metric_end_date) ? $this->metric_end_date : '',
            'id'             => $this->fb_ad_id,
        ];

        if( isSet($this->metric_time_increment) && $this->metric_time_increment )
        {
            $parameters['time_increment'] = $this->metric_time_increment;
        }

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("gender-statistics-" . implode('-', $parameters));

        // Define the closure that will be injected into the cache facade.
        //
        $closure = function() use( $parameters )
        {
            $query_string_fields = array_only($parameters, ['breakdowns', 'fields', 'start_date', 'end_date', 'time_increment']);
            $query_string = http_build_query($query_string_fields);

            $fb = connect_to_facebook();

            $results = $fb->get('/'.$parameters['id'].'/insights?' . $query_string, $parameters['account']->fb_token);

            $results = json_decode($results->getBody(), true);

            $results = array_only($results, ['data']);

            return isSet($results['data']) ? $results['data'] : $results;
        };

        return Cache::remember($identifier, 1, $closure);
    }

    /**
     * Define accessor that will fetch the device statistics.
     *
     * @return array
     */
    public function getDeviceStatisticsAttribute()
    {
        $parameters = [
            'account'        => $this->ad_set->campaign->account,
            'breakdowns'     => 'impression_device',
            'fields'         => 'placement,spend,cpm,ctr,cpc,frequency',
            'start_date'     => isSet($this->metric_start_date) ? $this->metric_start_date : '',
            'end_date'       => isSet($this->metric_end_date) ? $this->metric_end_date : '',
            'id'             => $this->fb_ad_id,
        ];

        if( isSet($this->metric_time_increment) && $this->metric_time_increment )
        {
            $parameters['time_increment'] = $this->metric_time_increment;
        }

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("device-statistics-" . implode('-', $parameters));

        // Define the closure that will be injected into the cache facade.
        //
        $closure = function() use( $parameters )
        {
            $query_string_fields = array_only($parameters, ['breakdowns', 'fields', 'start_date', 'end_date', 'time_increment']);
            $query_string = http_build_query($query_string_fields);

            $fb = connect_to_facebook();

            $results = $fb->get('/'.$parameters['id'].'/insights?' . $query_string, $parameters['account']->fb_token);

            $results = json_decode($results->getBody(), true);

            $results = array_only($results, ['data']);

            return isSet($results['data']) ? $results['data'] : $results;
        };

        return Cache::remember($identifier, 1, $closure);
    }

    /**
     * Define accessor that will fetch the placement statistics.
     *
     * @return array
     */
    public function getPlacementStatisticsAttribute()
    {
        $parameters = [
            'account'        => $this->ad_set->campaign->account,
            'breakdowns'     => 'placement',
            'fields'         => 'placement,spend,cpm,ctr,cpc,frequency',
            'start_date'     => isSet($this->metric_start_date) ? $this->metric_start_date : '',
            'end_date'       => isSet($this->metric_end_date) ? $this->metric_end_date : '',
            'id'             => $this->fb_ad_id,
        ];

        if( isSet($this->metric_time_increment) && $this->metric_time_increment )
        {
            $parameters['time_increment'] = $this->metric_time_increment;
        }

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("placement-statistics-" . implode('-', $parameters));

        // Define the closure that will be injected into the cache facade.
        //
        $closure = function() use( $parameters )
        {
            $query_string_fields = array_only($parameters, ['breakdowns', 'fields', 'start_date', 'end_date', 'time_increment']);
            $query_string = http_build_query($query_string_fields);

            $fb = connect_to_facebook();

            $results = $fb->get('/'.$parameters['id'].'/insights?' . $query_string, $parameters['account']->fb_token);

            $results = json_decode($results->getBody(), true);

            $results = array_only($results, ['data']);

            return isSet($results['data']) ? $results['data'] : $results;
        };

        return Cache::remember($identifier, 1, $closure);
    }

    /**
     * Define accessor that will fetch the campaign keyword data
     * from the Facebook ads api.
     */
    public function getKeywordStatisticsAttribute()
    {
        $parameters = [
            'account'        => $this->ad_set->campaign->account,
            'id'             => $this->fb_ad_id,
        ];

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("keyword-statistics-" . implode('-', $parameters));

        // Define the closure that will be injected into the cache facade.
        //
        $closure = function() use( $parameters )
        {
            $fb = connect_to_facebook();

            $results = $fb->get('/'.$parameters['id'].'/keywordstats', $parameters['account']->fb_token);

            $results = json_decode($results->getBody(), true);

            $results = array_only($results, ['data']);

            return isSet($results['data']) ? $results['data'] : $results;
        };

        return Cache::remember($identifier, 1, $closure);
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
        $id             = $this->fb_ad_id;
        $time_increment = $this->metric_time_increment;

        // This will serve as the cache key name.
        //
        $identifier = Str::slug("{$id} {$start_date} {$end_date} {$time_increment}");

        // Assign account to a variable so that we can inject it
        // into the cache closure.
        //
        $account = $this->ad_set->campaign->account;

        // How many minutes do we want the cache to remember this information?
        //
        $minutes_to_remember = 1;

        // Define the closure that will be used by the cache facade.
        //
        $closure = function() use($account, $fields, $id, $start_date, $end_date, $time_increment)
        {
            return $account
                ->accessFacebook()
                ->getAdGroup( $id )
                ->getInsightsBetweenDates( $fields, $start_date, $end_date, $time_increment );
        };

        return Cache::remember($identifier, $minutes_to_remember, $closure);
    }

    /**
     * Return all rules of the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rules()
    {
        return $this->morphMany( Rule::class, 'control');
    }

    public function rule_applications()
    {
        return $this->hasMany('App\RuleApplication', 'ref_id')
                    ->where('layer', '=', App\RuleApplication::LAYER_AD);
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

    /**
     * Return all utm codes for the ad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function utm_codes()
    {
        return $this->hasMany( UtmCode::class );
    }
}
