<?php

namespace App\Repositories;

use App\AdCreative;
use App\Contracts\Repositories\AdCreativeRepository as AdCreativeRepositoryContract;
use App\Support\Repository\Traits\Repositories;

class AdCreativeRepository implements AdCreativeRepositoryContract
{
    use Repositories;

    /**
     * @var AdCreative
     */
    protected $model;

    /**
     * Create new AdCreativeRepository instance.
     *
     * @param AdCreative $adCreative
     */
    public function __construct( AdCreative $adCreative )
    {
        $this->model = $adCreative;
    }
}