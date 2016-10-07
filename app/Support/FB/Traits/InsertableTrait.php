<?php

namespace App\Support\FB\Traits;

trait InsertableTrait {

    /**
     * Create the record within Facebook.
     *
     * @return mixed
     */
    public function create()
    {
        return $this->getPrimaryObject()->create();
    }

    /**
     * Set the data that will be submitted to Facebook.
     *
     * @param array $data
     *
     * @return $this
     */
    public function setFacebookData( $data = [] )
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