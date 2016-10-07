<?php 

namespace App\Contracts\Repositories;

use App\User;

interface UserRepository
{
    /**
     * Update the user online timestamp.
     *
     * @param User $user
     *
     * @return bool
     */
    public function updateOnlineCheckAt( User $user );
}