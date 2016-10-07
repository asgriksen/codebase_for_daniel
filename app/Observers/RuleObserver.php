<?php

namespace App\Observers;

use App\Contracts\Repositories\AdRepository;
use App\Contracts\Repositories\AdSetRepository;
use App\Contracts\Repositories\CampaignRepository;
use App\Contracts\Repositories\RuleApplicationRepository;
use App\Rule;
use Illuminate\Support\Facades\Auth;

class RuleObserver
{
    /**
     * @var AdRepository
     */
    protected $adRepository;

    /**
     * @var AdSetRepository
     */
    protected $adSetRepository;

    /**
     * @var CampaignRepository
     */
    protected $campaignRepository;

    /**
     * @var RuleApplicationRepository
     */
    private $ruleApplicationRepository;

    /**
     * Ad IDs
     *
     * @var array
     */
    protected $ad_ids = [];

    /**
     * Ad Set IDs
     *
     * @var array
     */
    protected $ad_set_ids = [];

    /**
     * Campaign IDs
     *
     * @var array
     */
    protected $campaign_ids = [];

    /**
     * Instances to be saved.
     *
     * @var array
     */
    protected $instances = [];

    /**
     * @param AdRepository              $adRepository
     * @param AdSetRepository           $adSetRepository
     * @param CampaignRepository        $campaignRepository
     * @param RuleApplicationRepository $ruleApplicationRepository
     */
    public function __construct(
        AdRepository $adRepository,
        AdSetRepository $adSetRepository,
        CampaignRepository $campaignRepository,
        RuleApplicationRepository $ruleApplicationRepository
    ){
        $this->adRepository = $adRepository;
        $this->adSetRepository = $adSetRepository;
        $this->campaignRepository = $campaignRepository;
        $this->ruleApplicationRepository = $ruleApplicationRepository;

        $this->getIds();
    }

    /**
     * @param Rule $rule
     */
    public function created( Rule $rule )
    {
        $this->syncModels( $rule );
    }

    /**
     * @param Rule $rule
     */
    public function updated( Rule $rule )
    {
        $this->syncModels( $rule );
    }

    /**
     * Fetch the id numbers for campaigns, ad sets, and ads.
     *
     * @return void
     */
    public function getIds()
    {
        $this->campaign_ids = $this->getCampaignIds();
        $this->ad_set_ids   = $this->getAdSetIds();
        $this->ad_ids       = $this->getAdIds();
    }

    /**
     * Fetch all campaigns ids for the logged in user.
     *
     * @return mixed
     */
    public function getCampaignIds()
    {
        return $this->campaignRepository
                    ->byUser( Auth::user() )
                    ->lists('id')
                    ->toArray();
    }

    /**
     * Fetch all ad set ids for the logged in user.
     *
     * @return mixed
     */
    public function getAdSetIds()
    {
        return $this->adSetRepository
                    ->byCampaignIds( $this->campaign_ids )
                    ->lists('id')
                    ->toArray();
    }

    /**
     * Fetch all ad ids for the logged in user.
     *
     * @return mixed
     */
    public function getAdIds()
    {
        return $this->adRepository
                    ->byAdSetIds( $this->ad_set_ids )
                    ->lists('id')
                    ->toArray();
    }

    /**
     * Create a new Rule Application instance.
     *
     * @param $id
     * @param $type
     *
     * @return mixed
     */
    public function newRuleApplicationInstance( $id, $type )
    {
        $attributes = [
            'layer_id'   => $id,
            'layer_type' => $type
        ];

        return $this->ruleApplicationRepository->newInstance( $attributes );
    }

    /**
     * Sync models with the rule record.
     *
     * @param $rule
     */
    public function syncModels( $rule )
    {
        // Delete already assigned rule applications.
        $rule->applications()->delete();

        switch( $rule->layer )
        {
            case 'ADS':
                $this->syncAds( $rule );
            break;
            //-----------------------
            case 'AD_SETS':
                $this->syncAdSets( $rule );
            break;
            //-----------------------
            case 'CAMPAIGNS':
                $this->syncCampaigns( $rule );
            break;
        }

        // Attach applications to the rule.
        $rule->applications()->saveMany( $this->instances );
    }

    /**
     * Attach ads to the list of rule applications.
     *
     * @return void
     */
    public function syncAds()
    {
        foreach( $this->ad_ids as $ad_id )
        {
            $this->instances[] = $this->newRuleApplicationInstance($ad_id, 'App\Ad');
        }
    }

    /**
     * Attach ad sets to the list of rule applications.
     *
     * @return void
     */
    public function syncAdSets()
    {
        foreach( $this->ad_set_ids as $ad_set_ids )
        {
            $this->instances[] = $this->newRuleApplicationInstance($ad_set_ids, 'App\AdSet');
        }
    }

    /**
     * Attach campaigns to the list of rule applications.
     *
     * @return void
     */
    public function syncCampaigns()
    {
        foreach( $this->campaign_ids as $campaign_id )
        {
            $this->instances[] = $this->newRuleApplicationInstance($campaign_id, 'App\Campaign');
        }
    }
}