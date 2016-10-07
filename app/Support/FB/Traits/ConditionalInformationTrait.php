<?php

namespace App\Support\FB\Traits;

use FacebookAds\Object\Values\InsightsPresets;
use ReflectionClass;

trait ConditionalInformationTrait
{
    /**
     * Fetch insights for a common facebook field and interval.
     *
     * @param $field
     * @param $interval
     *
     * @return int|number
     */
    public function commonFacebookInsightByCondition( $field, $interval )
    {
        $interval = strtolower( $interval );

        // Proceed if the interval request exists within a known Facebook preset value.
        if( $this->presetExistsByValue( $interval ) )
        {
            $fields = [ $field ];

            $params = [
                'date_preset' => $interval
            ];

            /* If you had to specify data for a particular range *\/
            $params = array(
                'time_range' => array(
                    'since' => '2015-01-18',
                    'until' => '2015-09-30',
                ),
            );
            /**/

            // Fetch the insight information.
            $insights = $this->getPrimaryObject()
                             ->getInsights($fields, $params)
                             ->getArrayCopy();

            // Define closure that will be injected into array_map();
            //
            $closure = function($record)
            {
                return $record->getData();
            };

            $insights = array_map($closure, $insights);

            // Define closure that will be injected into array_map();
            //
            $closure = function($record) use($field)
            {
                return isSet($record[$field]) ? $record[$field] : 0;
            };

            // Return the total number of clicks.
            return array_sum( array_map( $closure, $insights ) );
        }

        return 0;
    }

    /**
     * Return how many clicks were received during a particular interval.
     *
     * @param $interval
     *
     * @return int
     */
    public function clicksByCondition( $interval )
    {
        return $this->commonFacebookInsightByCondition('clicks', $interval);
    }

    /**
     * Return the cost per click during a particular interval.
     *
     * @param $interval
     *
     * @return int
     */
    public function cpcByCondition( $interval )
    {
        return $this->commonFacebookInsightByCondition('cpc', $interval);
    }

    /**
     * Return the click through ratio during a particular interval.
     *
     * @param $interval
     *
     * @return int
     */
    public function ctrByCondition( $interval )
    {
        return $this->commonFacebookInsightByCondition('ctr', $interval);
    }

    /**
     * Return the frequency during a particular interval.
     *
     * @param $interval
     *
     * @return int
     */
    public function frequencyByCondition( $interval )
    {
        return $this->commonFacebookInsightByCondition('frequency', $interval);
    }

    /**
     * Return how many clicks were received during a particular interval.
     *
     * @param $interval
     *
     * @return int
     */
    public function impressionsByCondition( $interval )
    {
        return $this->commonFacebookInsightByCondition('impressions', $interval);
    }

    /**
     * Get the constant values from Facebook's Insights Presets class.
     *
     * @return array
     */
    public function getInsightPresetConstants()
    {
        return (new ReflectionClass(InsightsPresets::class))->getConstants();
    }

    /**
     * Determine if a particular value belongs to a known constant.
     *
     * @param $value
     *
     * @return bool
     */
    public function presetExistsByValue( $value )
    {
        // Take the value we're searching with and convert it to lowercase.
        $value = strtolower( $value );

        // Flip the key value pairs.
        $flipped = array_flip( $this->getInsightPresetConstants() );

        // Confirm that a valid constant exists.
        return isSet( $flipped[$value] );
    }
}