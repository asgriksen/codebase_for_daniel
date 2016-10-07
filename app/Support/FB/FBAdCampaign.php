<?php 

namespace App\Support\FB;

use App\Contracts\FB\FBObject;
use App\Support\FB\Traits\ConditionalInformationTrait;
use App\Support\FB\Traits\InsertableTrait;
use App\Support\FB\Traits\InsightableTrait;
use App\Support\FB\Traits\ModelableTrait;
use App\Support\FB\Traits\UpdateableTrait;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FBAdCampaign implements FBObject
{
    use ConditionalInformationTrait,
        InsertableTrait,
        InsightableTrait,
        ModelableTrait,
        UpdateableTrait;

    /**
     * @var Campaign
     */
    private $adCampaign;

    /**
     * Data needed for the campaign eloquent model.
     *
     * @var array
     */
    protected $model_field_mappings = [
        CampaignFields::ID           => 'fb_campaign_id',
        CampaignFields::NAME         => 'name',
        // CampaignFields::STATUS       => 'status',
        CampaignFields::CREATED_TIME => 'fb_created_at',
        CampaignFields::UPDATED_TIME => 'fb_updated_at',
    ];

    /**
     * Mapping of eloquent to Facebook field mappings required
     * for the creation of a new Facebook Ad Campaign record.
     *
     * @var array
     */
    protected $model_to_facebook_field_mappings = [
        'name' => CampaignFields::NAME,
        // 'status' => CampaignFields::STATUS
    ];

    /**
     * Create new FBAdCampaign instance.
     *
     * @param Campaign $adCampaign
     */
    public function __construct( Campaign $adCampaign )
    {
        $this->adCampaign = $adCampaign;
    }

    /**
     * Return the primary object.
     *
     * @return Campaign
     */
    public function getPrimaryObject()
    {
        return $this->adCampaign;
    }
}