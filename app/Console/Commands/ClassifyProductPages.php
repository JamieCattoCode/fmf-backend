<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\ProductPageRepository;
use Illuminate\Console\Command;
use App\Services\ProductPageClassifier;
use Throwable;

class ClassifyProductPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:classify {storeId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give a furniture type classification to each of the product pages in the DB.';

    protected $classifier;
    protected $productPageRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductPageClassifier $classifier, ProductPageRepository $productPageRepository)
    {
        parent::__construct();
        $this->classifier = $classifier;
        $this->productPageRepository = $productPageRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $storeId = $this->argument('storeId');
        if ($storeId) {
            $pages = $this->productPageRepository->getPagesByFurnitureStore($storeId);
            
            foreach ($pages as $page) {
                try {
                    $furnitureItem = $this->classifier->classifyProductPage($page);
                    if ($furnitureItem) {
                        $url = $furnitureItem->url;
                        $furnitureType = $furnitureItem->furniture_type;
                        $this->info("\nClassified $url as a $furnitureType.\n");
                    } else {
                        $this->info("Could not classify $page->url");
                    }

                } catch (Throwable $thr) {
                    $this->info("Error on page with ID $page->id.");
                    $this->info($thr->getMessage());
                }
            }
        }
    }
}
