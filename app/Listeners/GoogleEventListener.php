<?php

namespace App\Listeners;

use App\Contracts\Repositories\GoogleAccessTokenRepository;

class GoogleEventListener
{
    /**
     * @var GoogleAccessTokenRepository
     */
    private $repository;

    /**
     * @param GoogleAccessTokenRepository $repository
     */
    public function __construct( GoogleAccessTokenRepository $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Handle when the user successfully logs into Google.
     *
     * @param $user
     */
    public function onLogin( $user )
    {

    }

    /**
     * Register the listener for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe( $events )
    {
        $events->listen( 'google.login', 'App\Listeners\GoogleEventListener@onLogin' );
    }
}