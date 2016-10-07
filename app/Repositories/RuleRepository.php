<?php

namespace App\Repositories;

use App\Contracts\Repositories\RuleRepository as RuleRepositoryContract;
use App\Rule;
use App\Support\Repository\Traits\Repositories;
use App\User;

class RuleRepository implements RuleRepositoryContract
{
    use Repositories;

    /**
     * @var Rule
     */
    protected $model;

    /**
     * Create new RuleRepository instance.
     *
     * @param Rule $rule
     */
    public function __construct( Rule $rule )
    {
        $this->model = $rule;
    }

    /**
     * Associate rule with user.
     *
     * @param Rule $rule
     * @param User $user
     *
     * @return bool
     */
    public function associateRuleWithUser( Rule $rule, User $user )
    {
        return $rule->user()->associate( $user )->save();
    }

    /**
     * Return a rule within the users account.
     *
     * @param      $id
     * @param User $user
     *
     * @return mixed
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->rules()->find( $id );
    }

    /**
     * Return a rule within the users account.
     *
     * @param       $id
     * @param User  $user
     * @param array $with
     *
     * @return mixed
     */
    public function byIdAndUserWith( $id, User $user, $with = [] )
    {
        return $user->rules()->with( $with )->find( $id );
    }

    /**
     * Return a rule with applications within the users account.
     *
     * @param       $id
     * @param User  $user
     *
     * @return mixed
     */
    public function byIdAndUserWithApplications( $id, User $user  )
    {
        return $this->byIdAndUserWith($id, $user, ['applications', 'applications.layer']);
    }

    /**
     * Return all rules for a particular user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user )
    {
        return $user->rules;
    }

    /**
     * Return rules belonging to a particular user along with related data.
     *
     * @param User  $user
     * @param array $with
     *
     * @return mixed
     */
    public function byUserWith( User $user, $with = [] )
    {
        return Rule::with($with)->whereIn('user_id', [$user->id])->get();
    }

    /**
     * Fetch all rules with applications within a specified user account.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUserWithApplications( User $user )
    {
        return $this->byUserWith( $user, ['applications', 'applications.layer']);
    }

    /**
     * Create a new rule record and associate it with the user.
     *
     * @param User  $user
     * @param array $attributes
     * @param bool  $return_rule Return the rule record.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createForUser( User $user, $attributes = [], $return_rule = false )
    {
        if( $return_rule )
        {
            $rule = $this->create( $attributes );

            $this->associateRuleWithUser( $rule , $user );

            return $rule;
        }

        return $user->rules()->save( $this->newInstance($attributes) );
    }

    /**
     * Return a list of intervals.
     *
     * @return array
     */
    public function getIntervalsList()
    {
        // Fetch only the constants that start with TYPE_
        //
        $results = array_where_keys_are_prefixed($this->getModel()->getConstants(), 'TYPE_');

        // Now return just the array values from the $results.
        //
        return array_values( $results );
    }

    /**
     * Return a list of available layers.
     *
     * @return array
     */
    public function getLayersList()
    {
        return $this->getModel()->getEnumOptions('layer');
    }

    /**
     * Return a list of strategies.
     *
     * @return array
     */
    public function getStrategiesList()
    {
        return $this->getModel()->getEnumOptions('strategy');
    }
}