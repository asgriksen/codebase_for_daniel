<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserPreferenceRepository as UserPreferenceRepositoryContract;
use App\Support\Repository\Traits\Repositories;
use App\User;
use App\UserPreference;

class UserPreferenceRepository implements UserPreferenceRepositoryContract
{
    use Repositories;

    /**
     * @var UserPreference
     */
    private $model;

    /**
     * Create a new UserPreferenceRepository instance.
     *
     * @param UserPreference $userPreference
     */
    public function __construct( UserPreference $userPreference )
    {
        $this->model = $userPreference;
    }

    /**
     * Return all preferences for a user.
     *
     * @param User $user
     *
     * @return \Illuminate\Support\Collection|mixed|static
     */
    public function byUser( User $user )
    {
        return $user->preferences;
    }

    /**
     * Fetch a preference record by its User and key value.
     *
     * @param User $user
     * @param      $key
     *
     * @return mixed
     */
    public function byUserAndKey( User $user, $key )
    {
        return $user->preferences()->whereKey($key)->first();
    }

    /**
     * Create a new preference record and associate it with a user.
     *
     * @param User  $user
     * @param array $attributes
     *
     * @return mixed
     */
    public function createForUser( User $user, $attributes = [] )
    {
        return $user->preferences()->save( $this->newInstance($attributes) );
    }
}