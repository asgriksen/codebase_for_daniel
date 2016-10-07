<?php 

namespace App\Observers;

use App\Events\AccountWasCreated;

class AccountObserver
{
    /**
     * Observe when a new account has been created.s
     *
     * @param $account
     */
    public function created( $account )
    {
        // Fire the account was created event.
        //
        event( new AccountWasCreated($account) );
    }
}