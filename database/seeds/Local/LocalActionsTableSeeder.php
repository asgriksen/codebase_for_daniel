<?php

use App\Condition;
use Illuminate\Database\Seeder;

class LocalActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->insert( $this->getArrayMap() );
    }

    /**
     * Take a name an create an array that will be used
     * for generating a new action record.
     *
     * @param $name
     *
     * @return array
     */
    public function nameToArray( $name )
    {
        return [
            'name'       => $name,
            'created_at' => new DateTime
        ];
    }

    /**
     * Return the array map.
     *
     * @return array
     */
    public function getArrayMap()
    {
        return array_map($this->getClosure(), $this->getNames());
    }

    /**
     * Return the closure.
     *
     * @return Closure
     */
    public function getClosure()
    {
        return function( $record )
        {
            return $this->nameToArray( $record );
        };
    }

    /**
     * Return a list of names that we will produce records for.
     *
     * @return array
     */
    public function getNames()
    {
        return [
            'Run',
            'Pause',
        ];
    }
}
