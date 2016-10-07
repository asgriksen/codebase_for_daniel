<?php

namespace App\Jobs;

use App\GoogleAccessToken;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshGoogleAccessToken extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var GoogleAccessToken
     */
    public $googleAccessToken;

    /**
     * Create a new job instance.
     *
     * @param GoogleAccessToken $googleAccessToken
     */
    public function __construct( GoogleAccessToken $googleAccessToken )
    {
        $this->googleAccessToken = $googleAccessToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Refresh the token.
        $this->googleAccessToken->refreshToken();

        // Delete the job from the queue.
        $this->delete();
    }
}
