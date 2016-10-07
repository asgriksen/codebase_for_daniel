<?php

namespace App\Http\Controllers\Etsy;

use App\Contracts\Repositories\EtsyRequestTokenRepository;
use App\Http\Controllers\APIBaseController;
use App\Support\Etsy\Auth as EtsyAuth;
use Illuminate\Support\Facades\Auth;
use Request;

class AuthController extends APIBaseController
{
    /**
     * @var EtsyRequestTokenRepository
     */
    private $etsyRequestTokenRepository;

    /**
     * Create new AuthController instance.
     *
     * @param EtsyRequestTokenRepository $etsyRequestTokenRepository
     */
    public function __construct( EtsyRequestTokenRepository $etsyRequestTokenRepository )
    {
        $this->etsyRequestTokenRepository = $etsyRequestTokenRepository;
    }

    /**
     * Log the user into their Etsy account.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        $auth = new EtsyAuth();

        // Proceed if we have temporary credentials from Etsy.
        //
        if( $auth->hasTemporaryCredentials() )
        {
            // Store the token credentials to the users account.
            //
            return $this->etsyRequestTokenRepository->createForUser(
                Auth::user(),
                $auth->obtainTokenCredentials()
            );
        }

        // Redirect the user to the Etsy site so that they can approve
        // the VS ROI application to access their account.
        //
        return redirect( $auth->getLoginUrl() );
    }
}