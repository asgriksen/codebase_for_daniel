<?php

namespace App\Support\Google;

class Account extends Analytic
{
    /**
     * Return the list of management accounts.
     *
     * @return mixed
     */
    public function getManagementAccounts()
    {
        return $this->analytics()
                    ->management_accounts
                    ->listManagementAccounts();
    }

    public function getList()
    {
        $accounts = [];

        foreach( $this->getManagementAccounts() as $account )
        {
            $accounts[] = [
                'id'    => $account['id'],
                'name'  => $account['name'],
                // 'link'  => route('google.property', $account['id'])
            ];
        }

        return $accounts;
    }
}