<?php

namespace App\Support\FB\Traits;

trait UpdateableTrait {

    use ApplicableActionTrait;

    /**
     * Update the record within facebook.
     *
     * @return bool
     */
    public function update()
    {
        return $this->getPrimaryObject()->update();
    }

    /**
     * Set the data that will be submitted to Facebook.
     *
     * @param array $data
     *
     * @return $this
     */
    public function setFacebookUpdateData( $data = [] )
    {
        $new_data = [];

        foreach( $data as $key => $value )
        {
            if( isSet( $this->model_to_facebook_field_mappings[$key] ) )
            {
                $key = $this->model_to_facebook_field_mappings[$key];

                $new_data[$key] = $value;
            }
        }

        $this->getPrimaryObject()->setData( $new_data );

        return $this;
    }
}