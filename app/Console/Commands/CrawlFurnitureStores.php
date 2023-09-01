<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\FurnitureStoreRepository;
use Illuminate\Console\Command;

class CrawlFurnitureStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stores:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find furniture products by crawling the furniture stores in the database.';

    protected $furnitureStoreRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FurnitureStoreRepository $furnitureStoreRepository)
    {
        parent::__construct();
        $this->furnitureStoreRepository = $furnitureStoreRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all furniture stores from database
        $furnitureStores = $this->furnitureStoreRepository->getAllStores();
        // Call a crawler method for each one
        foreach ($furnitureStores as $store) {
            
        }
        // Store all of the items retrieved in the database
    }

    private function crawlStore()
    {
        
    }
}
