<?php

namespace App\Contracts\Repositories;
use App\Cart;
use App\User;
use App\CartCategory;

interface CartCategoryRepository
{
    /**
     * Fetch all categories with a cart owned by a particular user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byCartAndUser( Cart $cart, User $user );


    /**
     * Fetch cart category record by id within the users known carts
     *
     * @param int  $id   The id of the target cart category record.
     * @param User $user The user object.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUser( $id, User $user );

    /**
     * Fetch all categories with a cart owned by a particular user.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function byCartAndUserWithNested( Cart $cart, User $user, $parentCategory );


    /**
     * Fetch cart category record by id within the users known carts
     *
     * @param int  $id   The id of the target cart category record.
     * @param User $user The user object.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function byIdAndUserWithNested( $id, Cart $cart, User $user );
}