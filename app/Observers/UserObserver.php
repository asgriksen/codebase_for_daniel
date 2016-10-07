<?php 

namespace App\Observers;

use Carbon\Carbon;

class UserObserver
{
    /**
     * Observe when a user record is in the process of being created.
     *
     * @param $user
     */
    public function creating( $user )
    {
        // Generate a unique verify token.
        //
        $user->verify_token = $user->generateUniqueVerifyToken();

        // Assign a expiration date and time for the token.
        //
        $user->verify_token_expiry = Carbon::now()->addDays(7)->toDateTimeString();
    }
}