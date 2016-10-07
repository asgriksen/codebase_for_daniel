<?php

namespace App\Support\FB;

use App\Contracts\FB\FBObject;
use App\Support\FB\Traits\InsertableTrait;
use App\Support\FB\Traits\InsightableTrait;
use App\Support\FB\Traits\ModelableTrait;
use App\Support\FB\Traits\UpdateableTrait;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdFields;

class FBAdSet implements FBObject
{
    use InsertableTrait,
        InsightableTrait,
        ModelableTrait,
        UpdateableTrait;

    /**
     * @var AdSet
     */
    protected $adSet;

    /**
     * Data needed for the ad set eloquent model.
     *
     * @var array
     */
    protected $model_field_mappings = [
        AdSetFields::BUDGET_REMAINING  => 'budget_remaining',
        AdSetFields::CAMPAIGN_ID => 'fb_campaign_id',
        // AdSetFields::CAMPAIGN_STATUS   => 'status',
        AdSetFields::DAILY_BUDGET      => 'daily_budget',
        AdSetFields::LIFETIME_BUDGET   => 'lifetime_budget',
        AdSetFields::ID                => 'fb_adset_id',
        AdSetFields::NAME              => 'name',
        AdSetFields::CREATED_TIME      => 'fb_created_at',
        AdSetFields::UPDATED_TIME      => 'fb_updated_at',
    ];

    /**
     * Mapping of eloquent to Facebook field mappings required
     * for the creation of a new Facebook Ad Campaign record.
     *
     * @var array
     */
    protected $model_to_facebook_field_mappings = [
        'name'   => AdSetFields::NAME,
        // 'status' => AdSetFields::CAMPAIGN_STATUS
    ];

    /**
     * Define which ad groups fields we want to work with.
     *
     * @var array
     */
    protected $ad_fields = [
        // AdGroupFields::BID_AMOUNT,
        AdFields::ID,
        AdFields::NAME
    ];

    /**
     * Create a new FBAdSet instance.
     *
     * @param AdSet $adSet
     */
    public function __construct( AdSet $adSet )
    {
        $this->adSet = $adSet;
    }

    /**
     * Return the primary object.
     *
     * @return AdSet
     */
    public function getPrimaryObject()
    {
        return $this->adSet;
    }

    /**
     * Return the ad groups that belongs to the ad sets.
     *
     * @return array
     */
    public function getAds()
    {
        $closure = function( $record )
        {
            return new FBAdGroup( $record );
        };

        $array = $this->getPrimaryObject()
                      ->getAds( $this->ad_fields )
                      ->getArrayCopy();

        return array_map( $closure, $array );
    }
}