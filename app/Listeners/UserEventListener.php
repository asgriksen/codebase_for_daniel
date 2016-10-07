<?php 

namespace App\Listeners;

use App\Contracts\Repositories\UserRepository;

class UserEventListener
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create new UserEventListener instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle when the user successfully logs in.
     *
     * @param $user
     */
    public function onLogin( $user )
    {
        // Update the users online timestamp.
        //
        $this->userRepository->updateOnlineCheckAt( $user );
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
        $events->listen(
            'auth.login',
            'App\Listeners\UserEventListener@onLogin'
        );
    }
}