<?php

namespace App\Repositories;

use App\Condition;
use App\Contracts\Repositories\ConditionRepository as Contract;
use App\Support\Repository\Traits\Repositories;

class ConditionRepository implements Contract
{
    use Repositories;

    /**
     * The condition model.
     *
     * @var Condition
     */
    protected $model;

    /**
     * @param Condition $model
     */
    public function __construct( Condition $model )
    {
        $this->model = $model;
    }
}