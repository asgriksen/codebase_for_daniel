<?php

namespace App\Observers;

use App\GoogleAccessToken;

class GoogleAccessTokenObserver
{
    /**
     * Observe when a Google Access Token record is being updated.
     *
     * @param GoogleAccessToken $model
     */
    public function updating( GoogleAccessToken $model )
    {
        // Decode the json data
        $decoded = is_null($model->json) ? [] : json_decode($model->json);

        // Assign object data to appropriate column.
        foreach( $decoded as $key => $value )
        {
            $model->$key = $value;
        }

        $model->expires_at = true;
    }
}