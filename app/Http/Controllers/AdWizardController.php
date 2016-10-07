<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\AdRepository;
use App\Contracts\Repositories\AdCreativeRepository;
use App\Contracts\Repositories\AdSetRepository;
use App\Contracts\Repositories\CampaignRepository;
use App\Http\Requests;
use App\Http\Requests\StoreAdWizardRequest;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Input;

class AdWizardController extends APIBaseController
{
  /**
  * @var AdRepository
  */
  private $adRepository;
  /**
  * The ad creative repository implementation.
  *
  * @var AdCreativeRepository
  */
  private $adCreativeRepository;
  /**
  * The ad set repository implementation.
  *
  * @var AdSetRepository
  */
  protected $adSetRepository;
  /**
  * @var CampaignRepository
  */
  private $campaignRepository;

  /**
  * Create new AdController instance.
  *
  * @param AdRepository $adRepository
  * @param AdCreativeRepository $adCreativeRepository
  * @param AdSetRepository $adSetRepository
  * @param CampaignRepository $campaignRepository
  */
  public function __construct( AdRepository $adRepository, AdCreativeRepository $adCreativeRepository, AdSetRepository $adSetRepository, CampaignRepository $campaignRepository ) {
    $this->adRepository = $adRepository;
    $this->adCreativeRepository = $adCreativeRepository;
    $this->adSetRepository = $adSetRepository;
    $this->campaignRepository = $campaignRepository;
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  StoreAdWizardRequest $request
  *
  * @return Response
  */
  public function create( StoreAdWizardRequest $request )
  {
    try {
      // get first fb account
      $account = Auth::user()->accounts()->where('is_selected', 1)->first();

      $fb = $account->accessFacebook();

      // create campaign
      $campaign = $this->campaignRepository->create ( $request, $account );

      // create ad set
      $adsets = $this->adSetRepository->create ( $request, $campaign );

      // create creatives/ads
      $ads = $this->adRepository->create ( $request, $adsets );

      return $this->response([ 
        'account' => $account,
        'campaign' => $campaign,
        'adSets' => $adsets,
        'ads' => $ads
      ]);
    }
    catch(Exception $e) {
      return $this->setError([ 'FB' => [$e->getMessage()] ])
          ->error(null);
    }
  }
}
