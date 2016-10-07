<?php

namespace App\Repositories;

use App\Contracts\Repositories\EtsyRequestTokenRepository as Contract;
use App\EtsyRequestToken;
use App\Support\Repository\Traits\Repositories;
use App\User;

class EtsyRequestTokenRepository implements Contract
{
    use Repositories;

    /**
     * @var EtsyRequestToken
     */
    protected $model;

    /**
     * Create new EtsyRequestTokenRepository instance.
     *
     * @param EtsyRequestToken $etsyRequestToken
     */
    public function __construct( EtsyRequestToken $etsyRequestToken )
    {
        $this->model = $etsyRequestToken;
    }

    /**
     * Create a new Etsy request token record record for a user.
     *
     * @param User  $user
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createForUser( User $user, $attributes = [] )
    {
        return $user->etsy_request_token()->save(
            $this->newInstance( $attributes )
        );
    }
}