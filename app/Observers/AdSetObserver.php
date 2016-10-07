<?php 

namespace App\Observers;

use App\AdSet;
use App\Events\AdSetWasCreated;
use App\Events\AdSetWasUpdated;

class AdSetObserver
{
    /**
     * Observe when an ad set record has been created.
     *
     * @param AdSet $adSet
     */
    public function created( AdSet $adSet )
    {
        // If no Facebook data exists then fire the
        // ad set was created event.
        //
        if( empty( $adSet->getFacebookIdFields() ) )
        {
            event( new AdSetWasCreated( $adSet ) );
        }
    }

    /**
     * Observe when an ad set record has been updated.
     *
     * @param AdSet $adSet
     */
    public function updated( AdSet $adSet )
    {
        // If Facebook data exists then fire the
        // ad set was created event.
        //
        if( ! empty( $adSet->getFacebookIdFields() ) )
        {
            event( new AdSetWasUpdated( $adSet ) );
        }
    }
}