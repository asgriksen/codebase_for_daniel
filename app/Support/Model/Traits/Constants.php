<?php

namespace App\Support\Model\Traits;

use ReflectionClass;

trait Constants
{
    /**
     * Return the constants for this class.
     *
     * @return array
     */
    public function getConstants()
    {
        return (new ReflectionClass($this))->getConstants();
    }
}