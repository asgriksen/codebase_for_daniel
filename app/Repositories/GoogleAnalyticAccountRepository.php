<?php

namespace App\Repositories;

use App\Contracts\Repositories\GoogleAnalyticAccountRepository as Contract;
use App\GoogleAnalyticAccount;
use App\Support\Repository\Traits\Repositories;
use App\User;

class GoogleAnalyticAccountRepository implements Contract
{
    use Repositories;

    /**
     * @var GoogleAnalyticAccount
     */
    private $model;

    /**
     * Create new GoogleAnalyticAccountRepository instance.
     *
     * @param GoogleAnalyticAccount $googleAnalyticAccount
     */
    public function __construct( GoogleAnalyticAccount $googleAnalyticAccount )
    {
        $this->model = $googleAnalyticAccount;
    }

    /**
     * Return all Google Analytic accounts for a given user.
     *
     * @param User $user
     *
     * @return Illuminate\Database\Eloquent\Collection|null
     */
    public function byUser( User $user )
    {
        return $user->google_analytic_accounts;
    }
}