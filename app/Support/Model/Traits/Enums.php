<?php

namespace App\Support\Model\Traits;

use DB;

trait Enums {

    /**
     * Return the available enum options for a provided database field.
     *
     * @param $field_name
     *
     * @return array
     */
    public function getEnumOptions( $field_name )
    {
        // Fetch the column detail from the tables.
        //
        $field = DB::select("SHOW COLUMNS FROM {$this->getTable()} where Field = \"$field_name\" ")[0]->Type;

        // Find a enum pattern match within the $field. We will return any found $matches.
        //
        preg_match('/^enum\((.*)\)$/', $field, $matches);

        // Return an empty array should no results exist.
        //
        if( ! isSet( $matches[1] ) ) return [];

        // Split the string at each comma.
        //
        $array = explode( ',' , $matches[1] );

        // Define closure that will be injected into array_map()
        //
        $closure = function( $record )
        {
            return trim($record, "''");
        };

        // Take the array, map it, and return the results.
        //
        return array_map( $closure,  $array);
    }
}