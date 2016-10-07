<?php

if( ! function_exists('array_where_keys_are_prefixed'))
{
    /**
     * Return only the array that where the key value starts with a specified prefix.
     *
     * @param $array
     * @param $prefix
     *
     * @return array
     */
    function array_where_keys_are_prefixed($array, $prefix)
    {
        // Define the closure that will be used.
        //
        $closure = function( $key, $value ) use( $prefix )
        {
            return starts_with( $key , $prefix );
        };

        return array_where($array, $closure);
    }
}

if( ! function_exists('connect_to_facebook'))
{
    /**
     * Create a new api connection with Facebook.
     *
     * @return \Facebook\Facebook
     */
    function connect_to_facebook()
    {
        return Cache::get('facebook', function()
        {
            return new Facebook\Facebook([
                'app_id'                => config("facebook.app_id"),
                'app_secret'            => config('facebook.secret'),
                'default_graph_version' => 'v2.4'
            ]);
        });
    }
}

if( ! function_exists('time_increment'))
{
    /**
     * Confirm that value is a valid Facebook time increment value.
     *
     * @param $value
     *
     * @return bool|int|string
     */
    function time_increment( $value )
    {
        $acceptable_strings  = ['monthly', 'all_days'];
        $acceptable_integers = range(1, 90);

        // Check if the $value is a integer but in string format.
        //
        $is_number = preg_match('/\d+/', $value);

        // Update the value to be parsed as a integer if is identified as a number.
        //
        $value = $is_number ? (int) $value : $value;

        // If the value is a string and is in our list of acceptable string then
        // we will return the value.
        //
        if( is_string($value) && in_array( trim($value), $acceptable_strings ))
        {
            return $value;
        }

        // If the value is an integer and it exists within our number range
        // then return the value.
        //
        if( is_int($value) && in_array( $value, $acceptable_integers))
        {
            return $value;
        }

        // By default we will return false.
        //
        return false;
    }
}