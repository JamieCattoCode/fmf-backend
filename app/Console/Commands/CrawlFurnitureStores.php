<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\FurnitureStoreRepository;
use Illuminate\Console\Command;
use App\Http\Controllers\ContentCrawler;

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

        $bar = $this->output->createProgressBar(count($furnitureStores));

        $this->info("Beginning scrape of the furniture stores database.`");
        $bar->start();
        
        foreach ($furnitureStores as $store) 
        {
            $this->info("\nScraping from " . $store->url);
            $this->contentCrawler->crawl($store->id, false);
            $bar->advance();
        }

        $this->info("Database scrape complete.");

        $bar->finish();
    }
}
