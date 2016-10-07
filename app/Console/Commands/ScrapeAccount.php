<?php

namespace App\Console\Commands;

use App\Account;
use App\Ad;
use App\Campaign;
use App\Support\Facebook\AdsManager;
use App\Support\Facebook\TokenManager;
use Illuminate\Console\Command;

class ScrapeAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:accounts {account_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape facebook ads accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start Facebook ads info scraping...');

        $arguments = $this->argument();

        if(empty($arguments['account_id'])) {
            $accounts = Account::all();
            $this->info('***OPTION ALL***');
        }
        else {
            $account_id = $arguments['account_id'];
            $this->info('***OPTION ONE : ' . $account_id . '***');
            $accounts = array(Account::find($account_id));
        }
        
        $this->info('INFO: total number of accounts to scrape - ' . count($accounts));

        $fbTokenManager = TokenManager::getSharedInstance();

        foreach($accounts as $account)
        {
            $this->info('- Scraping account : [' . $account->id . ', ' . $account->name . ']...');

            if(!$fbTokenManager->isValidAccessToken($account) && !$fbTokenManager->extendAccessToken($account))
            {
                $this->error('  Invalid access token.');

                continue;
            }

            AdsManager::init($account->fb_token);

            $result = AdsManager::scrapeObjects($account);
        }

        $this->info('Finishing...');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['account_id', InputArgument::OPTIONAL, 'Account ID to scrap.', ''],
        ];
    }
}
