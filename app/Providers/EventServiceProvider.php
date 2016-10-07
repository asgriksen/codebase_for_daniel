<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\AccountWasCreated' => [
            'App\Listeners\ImportAccountFacebookData'
        ],
        'App\Events\AdWasCreated' => [
            'App\Listeners\CreateFacebookAd'
        ],
        'App\Events\AdWasUpdated' => [
            'App\Listeners\UpdateFacebookAd'
        ],
        'App\Events\AdSetWasCreated' => [
            'App\Listeners\CreateFacebookAdSet'
        ],
        'App\Events\AdSetWasUpdated' => [
            'App\Listeners\UpdateFacebookAdSet'
        ],
        'App\Events\CampaignWasCreated' => [
            'App\Listeners\CreateFacebookAdCampaign'
        ],
        'App\Events\CampaignWasUpdated' => [
            'App\Listeners\UpdateFacebookAdCampaign'
        ],
        'App\Events\CartWasCreated' => [
            'App\Listeners\CreateApi2Cart'
        ],
        'App\Events\ProductWasCreated' => [
            'App\Listeners\ImportProductCategoriesFromApi2Cart',
            'App\Listeners\ImportProductCategoriesFromEtsy'
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \App\Listeners\UserEventListener::class
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }
}
