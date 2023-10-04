<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\FurnitureStoreRepository;
use Illuminate\Console\Command;
use App\Services\ContentCrawler;

class CrawlFurnitureStores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stores:crawl {storeId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find furniture products by crawling the furniture stores in the database.';

    protected $furnitureStoreRepository;
    protected $contentCrawler;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FurnitureStoreRepository $furnitureStoreRepository, ContentCrawler $contentCrawler)
    {
        parent::__construct();
        $this->furnitureStoreRepository = $furnitureStoreRepository;
        $this->contentCrawler = $contentCrawler;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $furnitureStores = $this->furnitureStoreRepository->getAllStores();

        $storeId = $this->argument('storeId');

        if ($storeId) {
            $furnitureStore = $this->furnitureStoreRepository->getStoreById($storeId);
            $this->scrapeStore($furnitureStore);
        }
        else {
            $this->scrapeAllStores($furnitureStores);
        }
    }

    private function scrapeAllStores($furnitureStores)
    {
        $bar = $this->output->createProgressBar(count($furnitureStores));

        $this->info("Beginning scrape of the furniture stores database...\n\n");
        $bar->start();
        
        foreach ($furnitureStores as $store) 
        {
            $this->scrapeStore($store);
            $bar->advance();
        }

        $this->info("\n\nDatabase scrape complete.");

        $bar->finish();
    }

    private function scrapeStore($furnitureStore)
    {
        $this->info("\n\nScraping from " . $furnitureStore->url . "\n");
        $this->contentCrawler->crawl($furnitureStore->id, false);
    }
}
