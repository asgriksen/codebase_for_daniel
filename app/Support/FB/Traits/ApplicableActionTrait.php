<?php

namespace App\Support\FB\Traits;

trait ApplicableActionTrait
{
    /**
     * Update Facebook object to be paused.
     *
     * @return bool
     */
    public function pause()
    {
        $data = [ 'status' => 'PAUSED' ];

        return $this->setFacebookUpdateData( $data )->update();
    }

    /**
     * Update Facebook object to be active.
     *
     * @return bool
     */
    public function run()
    {
        $data = [ 'status' => 'ACTIVE' ];

        return $this->setFacebookUpdateData( $data )->update();
    }
}