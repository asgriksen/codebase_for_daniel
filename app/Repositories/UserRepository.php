<?php 

namespace App\Repositories;

use App\Contracts\Repositories\UserRepository as UserRepositoryContract;
use App\Support\Repository\Traits\Repositories;
use App\User;

class UserRepository implements UserRepositoryContract
{
    use Repositories;

    /**
     * The user model.
     *
     * @var User
     */
    protected $model;

    /**
     * Create new UserRepository instance.
     *
     * @param User $user
     */
    public function __construct( User $user )
    {
        $this->model = $user;
    }

    /**
     * Update the user online timestamp.
     *
     * @param User $user
     *
     * @return bool
     */
    public function updateOnlineCheckAt( User $user )
    {
        return $this->update( $user, ['online_check_at' => date(DB_DATE_FORMAT)]);
    }
}