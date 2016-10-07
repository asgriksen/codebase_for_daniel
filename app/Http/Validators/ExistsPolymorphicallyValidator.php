<?php

namespace App\Http\Validators;

use App;
use Illuminate\Support\Facades\Auth;

class ExistsPolymorphicallyValidator
{
    /**
     * Validate the input.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     */
    public function validate( $attribute, $value, $parameters )
    {
        $control      = explode('|', $value);
        $control_id   = $control[0];
        $control_type = $control[1];

        // Now we will query to see if the polymorphic record exists.
        return $control_type::whereId( $control_id )->exists();
    }
}