<?php

namespace App\Listeners;

use App\Contracts\Repositories\AccountRepository;
use App\Contracts\Repositories\AdRepository;
use App\Contracts\Repositories\AdSetRepository;
use App\Contracts\Repositories\CampaignRepository;
use App\Events\AccountWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportAccountFacebookData implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * @var AdRepository
     */
    private $adRepository;

    /**
     * @var AdSetRepository
     */
    private $adSetRepository;

    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * Create the event listener.
     *
     * @param AccountRepository  $accountRepository
     * @param AdRepository       $adRepository
     * @param AdSetRepository    $adSetRepository
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(
        AccountRepository $accountRepository,
        AdRepository $adRepository,
        AdSetRepository $adSetRepository,
        CampaignRepository $campaignRepository
    ){
        $this->accountRepository  = $accountRepository;
        $this->adRepository       = $adRepository;
        $this->adSetRepository    = $adSetRepository;
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Handle the event.
     *
     * @param  AccountWasCreated  $event
     * @return void
     */
    public function handle( AccountWasCreated $event )
    {
        // Access the account object.
        //
        $account = $event->account;

        // Access account's Facebook data.
        //
        $facebook = $account->accessFacebook();

        // Access the facebook account access token and extend it.
        //
        $extendedAccessToken = $facebook->getAccessToken()->extend();

        // Update and import data if token exists.
        //
        if( (bool) $extendedAccessToken )
        {
            // Access the ad account
            //
            $ad_account = $facebook->getAdAccount();

            // Update the account record.
            //
            $this->accountRepository->update(
                $account, [
                    'fb_token'          => $extendedAccessToken->getValue(),
                    'fb_token_expiry'   => $extendedAccessToken->getExpiresAt() == null ? null : $extendedAccessToken->getExpiresAt()->format(DB_DATE_FORMAT),
                    'name'              => $ad_account->name()
                ]
            );

            // Import account's campaigns.
            //
            $this->campaignRepository->importToAccount(
                $ad_account->campaigns(),
                $account
            );

            // Import ad sets related to the account
            //
            $this->adSetRepository->import(
                $ad_account->adSets()
            );

            // Import ads from the ad sets
            //
            $this->adRepository->importFromAdSets(
                $ad_account->adSets()
            );
        }
    }
}
