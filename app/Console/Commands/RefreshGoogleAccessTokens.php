<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\GoogleAccessTokenRepository;
use App\Jobs\RefreshGoogleAccessToken;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class RefreshGoogleAccessTokens extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:google-access-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh tokens that have or are about to expire.';

    /**
     * @var GoogleAccessTokenRepository
     */
    private $googleAccessTokenRepository;

    /**
     * Create a new command instance.
     *
     * @param GoogleAccessTokenRepository $googleAccessTokenRepository
     */
    public function __construct( GoogleAccessTokenRepository $googleAccessTokenRepository )
    {
        parent::__construct();

        $this->googleAccessTokenRepository = $googleAccessTokenRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Display a message in the console.
        $this->info('Extending Google access tokens has now begun.');

        // Fetch the token records that are soon to expire.
        $expiring_tokens = $this->googleAccessTokenRepository->soonToExpire();

        // Define a simple counter.
        $counter = 0;

        // Iterate through the list of $expiring_tokens and push jobs onto
        // the queue that will refresh each token record with a new token.
        foreach( $expiring_tokens as $token )
        {
            $this->dispatch(new RefreshGoogleAccessToken($token));

            // Increment the counter.
            $counter++;
        }

        // Display a message in the console.
        $this->info("$counter tokens(s) have been pushed to the queue for processing.");
    }
}
