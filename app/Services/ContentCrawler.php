<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Crawler\ProductPageCrawlObserver;
use App\Crawler\TestCrawlProfile;
use App\Repository\FurnitureStoreInterface;
use App\Repository\ProductPageInterface;
use Spatie\Crawler\Crawler as SpatieCrawler;

class ContentCrawler
{

    protected $url;
    protected $crawlProfile;
    protected $furnitureStoreRepo;
    protected $productPageRepo;

    public function __construct(Request $request, FurnitureStoreInterface $furnitureStoreRepo, ProductPageInterface $productPageRepo)
    {
        $this->crawlProfile = new TestCrawlProfile;
        $this->furnitureStoreRepo = $furnitureStoreRepo;
        $this->productPageRepo = $productPageRepo;
        set_time_limit(0);
    }

    public function crawl(string $storeId, bool $log=true)
    {
        ob_start(); // Start output buffering

        $furnitureStore = $this->furnitureStoreRepo->getStoreById($storeId);

        $crawler = SpatieCrawler::create()
        ->addCrawlObserver(new ProductPageCrawlObserver($this->furnitureStoreRepo, $this->productPageRepo,  $furnitureStore, $log))
        ->setTotalCrawlLimit(400)
        ->startCrawling($furnitureStore->url);

        $output = ob_get_contents(); // Store buffer in variable

        ob_end_clean(); // End buffering and clean up

        return $output;
    }

    public function crawlAll()
    {
        $furnitureStores = $this->furnitureStoreRepo->getAllStores();

        foreach ($furnitureStores as $store) 
        {
            $this->crawl($store->id, false);
        }
    }

}
