<?php

class HelpersTest extends TestCase
{
    /**
     * @test
     */
    public function test_desired_functionality_of_array_where_keys_are_prefixed()
    {
        // Define the array that we will test.
        //
        $array = [
            'TEST-0'      => 0,
            'TEST-1'      => 1,
            'SOMETHING-2' => 2,
            'THIS-3'      => 3
        ];

        // Define the prefix we will check for.
        //
        $prefix = 'TEST-';

        // Define the closure that will be used by array_where().
        //
        $closure = function( $key, $value ) use( $prefix )
        {
            return starts_with( $key , $prefix );
        };

        // Filter the array and return the results.
        //
        $results = array_where($array, $closure);

        // Define the array that we expect to be returned.
        //
        $results_expected = [
            'TEST-0'      => 0,
            'TEST-1'      => 1,
        ];

        // Now check that the results expected match that of what was returned.
        //
        $this->assertEquals($results_expected, $results);
    }

    /**
     * @test
     */
    public function test_implementation_of_array_where_keys_are_prefixed()
    {
        // Define the array that we will test.
        //
        $array = [
            'TEST-0'      => 0,
            'TEST-1'      => 1,
            'SOMETHING-2' => 2,
            'THIS-3'      => 3
        ];

        // Define the prefix we will check for.
        //
        $prefix = 'SOMETHING-';

        // Filter the array and return the results.
        //
        $results = array_where_keys_are_prefixed($array, $prefix);

        // Define the array that we expect to be returned.
        //
        $results_expected = [
            'SOMETHING-2' => 2,
        ];

        // Now check that the results expected match that of what was returned.
        //
        $this->assertEquals($results_expected, $results);
    }
}