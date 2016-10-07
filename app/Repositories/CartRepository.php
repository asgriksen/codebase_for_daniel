<?php

namespace App\Repositories;

use App\Cart;
use App\Contracts\Repositories\CartRepository as Contract;
use App\Support\Repository\Traits\Repositories;
use App\User;

class CartRepository implements Contract
{
    use Repositories;

    /**
     * @var Cart
     */
    private $model;

    /**
     * @param Cart $cart
     */
    public function __construct( Cart $cart )
    {
        $this->model = $cart;
    }

    /**
     * Fetch cart within the users list of known carts.
     *
     * @param int  $id
     * @param User $user
     *
     * @return mixed
     */
    public function byIdAndUser( $id, User $user )
    {
        return $user->carts()->find( $id );
    }

    /**
     * Retur carts for a particular user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byUser( User $user )
    {
        return $user->carts;
    }

    /**
     * Create a new cart record and associate it with a given user.
     *
     * @param User  $user
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createForUser( User $user, $attributes = [ ] )
    {
        return $user->carts()->save( $this->newInstance( $attributes ) );
    }
}