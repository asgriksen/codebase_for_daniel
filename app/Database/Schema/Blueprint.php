<?php

namespace App\Database\Schema;

use Illuminate\Database\Schema\Blueprint as LaravelBlueprint;

class Blueprint extends LaravelBlueprint
{
    /**
     * Create a new set column on the table.
     *
     * @param string $column
     * @param array  $allowed
     *
     * @return \Illuminate\Support\Fluent
     */
    public function set($column, $allowed = [] )
    {
        return $this->addColumn('set', $column, compact('allowed'));
    }
}