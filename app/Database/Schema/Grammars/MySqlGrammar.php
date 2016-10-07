<?php

namespace App\Database\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as LaravelMySqlGrammar;
use Illuminate\Support\Fluent;

class MySqlGrammar extends LaravelMySqlGrammar
{
    /**
     * Create the column definition for a set type.
     *
     * @param Fluent $column
     *
     * @return string
     */
    protected function typeSet( Fluent $column )
    {
        return "set('".implode("', '", $column->allowed)."')";
    }
}