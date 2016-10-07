<?php 

namespace App\Observers;

use App\Events\AdWasCreated;
use App\Events\AdWasUpdated;

class AdObserver
{
    /**
     * Observe when a new ad record has been created.
     *
     * @param $ad
     */
    public function created( $ad )
    {
        // Fire AdWasCreated event as long as no existing Facebook data exists
        //
        if( empty( $ad->getFacebookIdFields() ) )
        {
            event( new AdWasCreated( $ad ) );
        }
    }

    /**
     * Observe when a ad record has been updated.
     *
     * @param $ad
     */
    public function updated( $ad )
    {
        // Fire AdWasUpdated event as long as Facebook data exists.
        //
        if( ! empty( $ad->getFacebookIdFields() ) )
        {
            event( new AdWasUpdated( $ad ) );
        }
    }
}