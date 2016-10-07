<?php 

namespace App\Support\FB;

use App\Support\FB\Traits\InsightableTrait;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\AdSetFields;

class FBAdAccount
{
    use InsightableTrait;

    /**
     * @var AdAccount
     */
    private $adAccount;

    /**
     * Ad Set fields we want data from.
     *
     * @var array
     */
    protected $ad_set_fields = [
        AdSetFields::CREATED_TIME,
        // AdSetFields::CAMPAIGN_GROUP_ID,
        // AdSetFields::CAMPAIGN_STATUS,
        AdSetFields::DAILY_BUDGET,
        AdSetFields::ID,
        AdSetFields::LIFETIME_BUDGET,
        AdSetFields::NAME,
    ];

    /**
     * Campaign fields we want data from.
     *
     * @var array
     */
    protected $campaign_fields = [
        CampaignFields::ID,
        CampaignFields::NAME,
        // CampaignFields::STATUS
    ];

    /**
     * @param AdAccount $adAccount
     */
    public function __construct( AdAccount $adAccount )
    {
        $this->adAccount = $adAccount;
    }

    /**
     * Return the ad account name.
     *
     * @return $this
     */
    public function name()
    {
        return $this->adAccount->read([ AdAccountFields::NAME ])->{AdAccountFields::NAME};
    }

    /**
     * Return the account ad sets.
     *
     * @return array
     */
    public function adSets()
    {
        // Define the closure that will be used by array_map().
        //
        $closure = function( $record )
        {
            return new FBAdSet( $record );
        };

        // Define the array that will be used by array_map().
        //
        $array = $this->adAccount
                      ->getAdSets( $this->ad_set_fields )
                      ->getArrayCopy();

        // Map the data and return the new array.
        //
        return array_map( $closure, $array );
    }

    /**
     * Return the ad account campaign objects.
     *
     * @return array
     */
    public function campaigns()
    {
        // Define the closure that will be used by array_map().
        //
        $closure = function( $record )
        {
            return new FBAdCampaign( $record );
        };

        // Define the array that will be used by array_map().
        //
        $array = $this->adAccount
                      ->getCampaigns( $this->campaign_fields )
                      ->getArrayCopy();

        // Map the data and return the new array.
        //
        return array_map( $closure, $array );
    }

    /**
     * Return the primary object.
     *
     * @return AdAccount
     */
    public function getPrimaryObject()
    {
        return $this->adAccount;
    }
}