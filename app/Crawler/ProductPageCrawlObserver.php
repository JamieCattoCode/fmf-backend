<?php
namespace App\Crawler;

use App\Models\FurnitureStore;
use App\Repository\FurnitureStoreInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;

class ProductPageCrawlObserver extends CrawlObserver {

    private $productPageCount = 0;
    private $furnitureStoreRepo;
    private $furnitureStore;
    private $log;

    public function __construct(FurnitureStoreInterface $furnitureStoreRepository, FurnitureStore $furnitureStore, bool $log=false)
    {
        $this->furnitureStoreRepo = $furnitureStoreRepository;
        $this->furnitureStore = $furnitureStore;
        $this->log = $log;
    }

    public function willCrawl(UriInterface $url): void {}

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $crawler = new Crawler($response->getBody());
        if ($this->isProductPage($crawler)) {
            $this->productPageCount += 1;
            if ($this->log) {
                $this->logProductPage($url);
            }
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        if ($this->log) {
            echo 'FAILED to crawl ' . $url->getPath() . '<br>';
        }
    }

    public function finishedCrawling(): void
    {
        $this->storeProductPages();
        if($this->log) {
            echo 'Crawling finished - found ' . $this->productPageCount . ' pages.<br>';
        }
    }

    private function isProductPage(Crawler $crawler): bool
    {
        $basketBtn = $this->getBasketButtons($crawler);
        if ($basketBtn->getNode(0)) {
            return true;
        }
        return false;
    }

    private function getBasketButtons(Crawler $crawler)
    // //button[span[contains(text(), 'basket')] or span[contains(text(), 'bag')] or span[contains(text(), 'cart')]]
    {
        return $crawler->filterXPath(
            "//button[span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'basket')] 
            or span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'bag')] 
            or span[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'cart')]]"
        );
    }

    private function logProductPage($url)
    {
        echo '<br>';
        echo $url->getPath() . ' is a product page.<br>';
        echo 'Current count: ' . $this->productPageCount . '<br>';
        echo '<br>';
    }

    private function storeProductPages()
    {
        $this->furnitureStoreRepo->setNumProductPages($this->furnitureStore->id, $this->productPageCount);
    }

}