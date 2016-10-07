<?php

namespace App\Contracts\Repositories;

interface RuleRepository
{
    /**
     * Return a list of intervals.
     *
     * @return array
     */
    public function getIntervalsList();

    /**
     * Return a list of strategies.
     *
     * @return array
     */
    public function getStrategiesList();
}