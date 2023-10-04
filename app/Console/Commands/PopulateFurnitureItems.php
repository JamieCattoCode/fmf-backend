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
        } else {
            $items = $this->furnitureItemRepository->getAllFurnitureItems();
        }
        
        $this->populateFurnitureItems($items);
    }

    private function populateFurnitureItems($items)
    {
        foreach ($items as $furnitureItem) {
            $this->populateFurnitureItem($furnitureItem);
        }
    }

    private function populateFurnitureItem($item) 
    {
        try {
            $productDetails = $this->extractor->extractDetails($item);

            $title = $productDetails['title'];
            $price = $productDetails['price'];
            $dimensions = $productDetails['dimensions'];
            $img = $productDetails["img"];

            $height = $dimensions['height'];
            $width = $dimensions['width'];
            $depth = $dimensions['depth'];

            $this->info("$title | $price | $height x $width x $depth\n");

            $item->update([
                "title" => $title,
                "price" => $price,
                "height" => $height,
                "width" => $width,
                "depth" => $depth,
                "img" => $img
            ]);
            
        } catch (\Throwable $th) {
            $this->info("Error on page with ID $item->id.");
            $this->info($th->getMessage());
        }
    }
}
