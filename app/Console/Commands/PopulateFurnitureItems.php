<?php

namespace App\Console\Commands;

use App\Repository\Eloquent\FurnitureItemRepository;
use App\Repository\Eloquent\FurnitureStoreRepository;
use App\Repository\Eloquent\ProductPageRepository;
use App\Services\FurnitureDetailsExtractor;
use Illuminate\Console\Command;

class PopulateFurnitureItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:populate {storeId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the furniture items in the DB with title, price, and dimensions.';

    protected $furnitureItemRepository;
    protected $extractor;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FurnitureDetailsExtractor $extractor, FurnitureItemRepository $furnitureItemRepository)
    {
        parent::__construct();
        $this->extractor = $extractor;
        $this->furnitureItemRepository = $furnitureItemRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $storeId = $this->argument('storeId');
        if($storeId) {
            $items = $this->furnitureItemRepository->getItemsByFurnitureStore($storeId);

            foreach ($items as $furnitureItem) {
                try {
                    $productDetails = $this->extractor->extractDetails($furnitureItem);

                    $title = $productDetails['title'];
                    $price = $productDetails['price'];
                    $dimensions = $productDetails['dimensions'];

                    $height = $dimensions['height'];
                    $width = $dimensions['width'];
                    $depth = $dimensions['depth'];

                    $this->info("$title | $price | $height x $width x $depth\n");

                    $furnitureItem->update([
                        "title" => $title,
                        "price" => $price,
                        "height" => $height,
                        "width" => $width,
                        "depth" => $depth,
                    ]);
                    
                } catch (\Throwable $th) {
                    $this->info("Error on page with ID $furnitureItem->id.");
                    $this->info($th->getMessage());
                }
            }
        }
    }
}
