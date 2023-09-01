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

    public function __construct(FurnitureStoreInterface $furnitureStoreRepository, FurnitureStore $furnitureStore)
    {
        $this->furnitureStoreRepo = $furnitureStoreRepository;
        $this->furnitureStore = $furnitureStore;
    }

    public function willCrawl(UriInterface $url): void {}

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $crawler = new Crawler($response->getBody());
        if ($this->isProductPage($crawler)) {
            $this->productPageCount += 1;
            // echo '<br>';
            // echo $url->getPath() . ' is a product page.<br>';
            // echo 'Current count: ' . $this->productPageCount . '<br>';
            // echo '<br>';
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): void
    {
        echo 'FAILED to crawl ' . $url->getPath() . '<br>';
    }

    public function finishedCrawling(): void
    {
        $this->furnitureStoreRepo->setNumProductPages($this->furnitureStore->id, $this->productPageCount);
        echo 'Crawling finished - found ' . $this->productPageCount . ' pages.<br>';
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
    {
        return $crawler->filterXPath("//button[span[contains(text(), 'basket')]]");
    }

}