<?php

namespace App\Support\FB\Traits;

trait ModelableTrait {

    /**
     * Return data for the campaign model.
     *
     * @return array
     */
    public function getDataForModel()
    {
        // The fields we want to read.
        //
        $fields = $this->getModelFieldMappingKeys();

        // Fetch only the fields from the that we need in order
        // to insert data into the eloquent model.
        //
        $data = array_only(
                    $this->getPrimaryObject()->read($fields)->getData(),
                    $fields
                );

        // Create array that will later be returned.
        //
        $array = [ ];

        // Iterate through the data and map it to the
        // appropriate model fields.
        //
        foreach ( $data as $key => $value )
        {
            if ( $this->modelFieldMappingExists($key) )
            {
                $key = $this->getModelFieldMapping($key);

                $array[ $key ] = $value;
            }
        }

        // Return the mapped data.
        //
        return $array;
    }

    /**
     * Return the mapped model field.
     *
     * @param $key
     *
     * @return mixed
     */
    public function getModelFieldMapping($key)
    {
        return $this->getModelFieldMappings()[$key];
    }

    /**
     * Return the model field mapping keys.
     *
     * @return array
     */
    private function getModelFieldMappingKeys()
    {
        return array_keys( $this->getModelFieldMappings() );
    }

    /**
     * Return the model field mappings.
     *
     * @return array
     */
    private function getModelFieldMappings()
    {
        return $this->model_field_mappings;
    }

    /**
     * Return if a particular mapping exists.
     *
     * @param $key
     *
     * @return bool
     */
    private function modelFieldMappingExists( $key )
    {
        return null !== $this->getModelFieldMapping( $key );
    }
}