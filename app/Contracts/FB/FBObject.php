<?php 

namespace App\Contracts\FB;

interface FBObject
{
    /**
     * Return the primary object.
     *
     * @return AdSet
     */
    public function getPrimaryObject();
}