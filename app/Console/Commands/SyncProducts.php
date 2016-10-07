<?php

namespace App\Console\Commands;

use App\Contracts\Repositories\ProductRepository;
use App\Jobs\SyncProduct;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SyncProducts extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync local product with api product record.';

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * Create a new command instance.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct( ProductRepository $productRepository )
    {
        parent::__construct();

        $this->productRepository = $productRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Syncing products has now begun.');

        // Fetch all product records from the database.
        //
        $products = $this->productRepository->all();

        $counter = 0;

        foreach( $products as $product )
        {
            $this->dispatch(new SyncProduct($product));

            $counter++;
        }

        $this->info("$counter product(s) have been pushed to the queue for processing.");
    }
}
