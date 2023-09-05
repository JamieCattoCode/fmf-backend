<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\BrowserKit\Client as BrowserClient;
use Exception;
use App\Crawler\ProductPageCrawlObserver;
use App\Crawler\TestCrawlProfile;
use App\Repository\FurnitureStoreInterface;
use Spatie\Crawler\Crawler as SpatieCrawler;

class ContentCrawler extends Controller
{

    protected $url;
    protected $crawlProfile;
    protected $furnitureStoreRepo;

    public function __construct(Request $request, FurnitureStoreInterface $furnitureStoreRepo)
    {
        $this->crawlProfile = new TestCrawlProfile;
        $this->furnitureStoreRepo = $furnitureStoreRepo;
        set_time_limit(0);
    }

    public function crawl(string $storeId, bool $log=true)
    {
        ob_start(); // Start output buffering

        $furnitureStore = $this->furnitureStoreRepo->getStoreById($storeId);

        $crawler = SpatieCrawler::create()
        ->addCrawlObserver(new ProductPageCrawlObserver($this->furnitureStoreRepo, $furnitureStore, $log))
        // ->setCrawlProfile($this->crawlProfile)
        ->setTotalCrawlLimit(200)
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

    public function fullUrl() {
        echo $this->furnitureStoreRepo->getStoreByUrl($this->url);
    }

}
