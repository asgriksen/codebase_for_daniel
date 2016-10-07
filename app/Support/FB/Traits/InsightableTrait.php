<?php

namespace App\Support\FB\Traits;

trait InsightableTrait {

    /**
     * Return the insights.
     *
     * @param array $fields
     * @param array $parameters
     *
     * @return mixed
     */
    public function getInsights( $fields = [], $parameters = [] )
    {
        return $this->getPrimaryObject()
                    ->getInsights( $fields, $parameters )
                    ->getArrayCopy();
    }

    /**
     * Return the insight data.
     *
     * @param array           $fields         The data field we want Facebook to return.
     * @param string          $start_date     The start date in format yyyy-mm-dd.
     * @param string          $end_date       The start date in format yyyy-mm-dd.
     * @param bool|string|int $time_increment Metric data breakdown.
     *
     * @return array
     */
    public function getInsightsBetweenDates( $fields = [], $start_date, $end_date, $time_increment = false )
    {
        $params = [
            'time_range' => [
                'since' => $start_date,
                'until' => $end_date
            ],
        ];

        // Add time increment if applicable.
        //
        if( (bool) time_increment( $time_increment ) )
        {
            $params['time_increment'] = $time_increment;
        }

        // Obtain the insight data array from Facebook.
        //
        $insights = $this->getInsights( $fields, $params );

        // Define closure that will be injected into array_map();
        //
        $closure = function( $record )
        {
            return $record->getData();
        };

        return array_map($closure, $insights);
    }
}