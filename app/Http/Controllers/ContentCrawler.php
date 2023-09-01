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
    }

    // public function crawl()
    // {
    //     ob_start(); // Start output buffering

    //     $crawler = SpatieCrawler::create()
    //     ->addCrawlObserver(new ProductPageCrawlObserver($this->furnitureStoreRepo, ))
    //     ->setCrawlProfile($this->crawlProfile)
    //     ->setTotalCrawlLimit(300)
    //     ->startCrawling($this->url);

    //     $output = ob_get_contents(); // Store buffer in variable

    //     ob_end_clean(); // End buffering and clean up

    //     return $output;
    // }

    public function crawlAll()
    {
        ob_start();

        $furnitureStores = $this->furnitureStoreRepo->getAllStores();

        foreach ($furnitureStores as $store) 
        {
            $crawler = SpatieCrawler::create()
                ->addCrawlObserver(new ProductPageCrawlObserver($this->furnitureStoreRepo, $store))
                ->setCrawlProfile($this->crawlProfile)
                ->setTotalCrawlLimit(300)
                ->startCrawling($store->url);
        }

        ob_end_clean();
    }

    public function fullUrl() {
        echo $this->furnitureStoreRepo->getStoreByUrl($this->url);
    }

}
