<?php

namespace App\Jobs;

use App\Product;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncProduct extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Product
     */
    protected $product;

    /**
     * Create a new job instance.
     *
     * @param Product $product
     */
    public function __construct( Product $product )
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Connect to the product's api account.
        $api = $this->product->cart->connectWithApi2CartProductApi();

        // Define which parameters we're interested in. For now
        // we're only interested in obtaining the available quantity.
        //
        $params = 'quantity';

        // Fetch the product record from the api.
        $product = $api->getInfo($this->product->external_id, $params);

        // Now we update the local record with new data.
        //
        $this->product->fill((array) $product)->save();

        // Delete the job from the queue.
        //
        $this->delete();
    }
}
