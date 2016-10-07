<?php

namespace App\Repositories;

use App\Contracts\Repositories\RuleApplicationRepository as RepositoryContract;
use App\RuleApplication;
use App\Support\Repository\Traits\Repositories;
use App\User;

class RuleApplicationRepository implements RepositoryContract
{
    use Repositories;

    /**
     * @var Rule
     */
    protected $model;

    /**
     * Create new RuleRepository instance.
     *
     * @param RuleApplication $ruleApplication
     */
    public function __construct( RuleApplication $ruleApplication )
    {
        $this->model = $ruleApplication;
    }

    /**
     * Return a specific rule application record within the users account.
     *
     * @param      $id
     * @param User $user
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->ruleApplications()->find($id);
    }

    /**
     * Return the users rule applications.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user )
    {
        return $user->ruleApplications;
    }

    /**
     * Return a list of layers.
     *
     * @return array
     */
    public function getLayersList()
    {
        // Fetch only the constants that start with TYPE_
        //
        $results = array_where_keys_are_prefixed($this->getModel()->getConstants(), 'LAYER_');

        // Now return just the array values from the $results.
        //
        return array_values( $results );
    }
}