<?php

use App\Condition;
use Illuminate\Database\Seeder;

class LocalConditionsTableSeeder extends Seeder
{
    /**
     * List of comparison options.
     *
     * @var array
     */
    protected $comparison_options = Condition::COMPARISON_OPTIONS;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->insert( $this->getArrayMap() );
    }

    /**
     * Take a name an create an array that will be used
     * for generating a new condition record.
     *
     * @param $name
     *
     * @return array
     */
    public function nameToArray( $name )
    {
        return [
            'name'               => $name,
            'comparison_options' => implode(',', $this->comparison_options),
            'created_at'         => new DateTime
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
            'Facebook Statistics',
            'Ad Set',
            'Campaign Group',
            'Clicks',
            'Conversion Rate',
            'COS',
            'CPC',
            'CPT',
            'CTR',
            'Current Bid',
            'Current Bid CPC',
            'Current Bid CPM',
            'Current Bid CPA',
            'Current State',
            'Frequency',
            'Impressions',
            'Name',
            'OCPM Click',
            'OCPM Actions',
            'OCPM Reach',
            'OCPM Social',
            'Placement',
            'Revenue',
            'ROI',
            'Spent',
            'Transactions',
        ];
    }
}
