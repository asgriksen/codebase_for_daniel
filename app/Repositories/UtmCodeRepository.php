<?php

namespace App\Repositories;

use App\Contracts\Repositories\UtmCodeRepository as Contract;
use App\Support\Repository\Traits\Repositories;
use App\User;
use App\UtmCode;

class UtmCodeRepository implements Contract
{
    use Repositories;

    /**
     * Model used by the repository.
     *
     * @var UtmCode
     */
    protected $model;

    /**
     * Create a new UtmCodeRepository instance.
     *
     * @param UtmCode $utmCode
     */
    public function __construct( UtmCode $utmCode )
    {
        $this->model = $utmCode;
    }

    /**
     * Fetch utm code by ID within a user account.
     *
     *
     * @param int  $id
     * @param User $user
     *
     * @return mixed
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->utm_codes()->find( $id );
    }

    /**
     * Return all Utm Codes for a particular user.
     *
     * @param User $user
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function byUser( User $user )
    {
        return $user->utm_codes;
    }
}