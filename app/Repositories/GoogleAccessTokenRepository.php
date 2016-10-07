<?php

namespace App\Repositories;

use App\Contracts\Repositories\GoogleAccessTokenRepository as Contract;
use App\GoogleAccessToken;
use App\Support\Repository\Traits\Repositories;
use App\User;
use Carbon\Carbon;

class GoogleAccessTokenRepository implements Contract
{
    use Repositories;

    /**
     * @var GoogleAccessToken
     */
    private $model;

    /**
     * Create new GoogleAccessTokenRepository instance.
     *
     * @param GoogleAccessToken $googleAccessToken
     */
    public function __construct( GoogleAccessToken $googleAccessToken )
    {
        $this->model = $googleAccessToken;
    }

    /**
     * Return the Google Access Token record for a given user.
     *
     * @param User $user
     *
     * @return GoogleAccessToken|null
     */
    public function byUser( User $user )
    {
        return $user->google_access_token;
    }

    /**
     * Create a new record based on supplied json data from Google.
     *
     * @param User   $user
     * @param string $json
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createForUserFromJson( User $user, $json = '' )
    {
        $token = $user->google_access_token()->firstOrCreate([]);

        $token->json = $json;
        $token->save();

        return $token;
    }

    /**
     * Logout the user from Google.
     *
     * @param User $user
     *
     * @return bool
     */
    public function logoutUser( User $user )
    {
        // If the user has a current google access token record then
        // we need to revoke it from being used any more.
        //
        if( $user->google_acces_token )
        {
            $user->google_access_token->revokeToken();
        }

        // If the user has a google access token in their session data
        // we will need to destroy that as well.
        //
        if( session()->has('token') )
        {
            session()->forget('token');
        }

        return true;
    }

    /**
     * Return records that expire within fifteen minutes.
     *
     * @return mixed
     */
    public function soonToExpire()
    {
        $fifteen_before = Carbon::now()->subMinutes(15)->toDateTimeString();
        $fifteen_later  = Carbon::now()->addMinutes(15)->toDateTimeString();

        return $this->getModel()->whereBetween('expires_at', [$fifteen_before, $fifteen_later])->get();
    }
}