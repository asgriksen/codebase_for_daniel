<?php

namespace App\Observers;

use App\Contracts\Repositories\CartRepository;
use App\EtsyRequestToken;

class EtsyRequestTokenObserver
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @param CartRepository $cartRepository
     */
    public function __construct( CartRepository $cartRepository )
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Observe when a Etsy requset token record has been created.
     *
     * @param EtsyRequestToken $model
     */
    public function created( EtsyRequestToken $model )
    {
        // Generate a shopping cart record.
        $this->cartRepository->createForUser(
            $model->user, [
                'etsy_id' => $model->id
            ]
        );
    }
}