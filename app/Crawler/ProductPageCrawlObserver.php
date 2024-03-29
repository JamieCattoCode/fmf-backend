<?php
namespace App\Crawler;

use App\Models\FurnitureStore;
use App\Repository\FurnitureStoreInterface;
use App\Repository\ProductPageInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;
use Symfony\Component\DomCrawler\Crawler;

class ProductPageCrawlObserver extends CrawlObserver {

    private $productPageCount = 0;
    private $furnitureStoreRepo;
    private $productPageRepo;
    private $furnitureStore;
    private $log;

    public function __construct(
        FurnitureStoreInterface $furnitureStoreRepository, 
        ProductPageInterface $productPageRepository, 
        FurnitureStore $furnitureStore, 
        bool $log=false
    )
    {
        $this->furnitureStoreRepo = $furnitureStoreRepository;
        $this->productPageRepo = $productPageRepository;
        $this->furnitureStore = $furnitureStore;
        $this->log = $log;
    }

    public function willCrawl(UriInterface $url): void {
        if ($this->log) {
            echo 'About to crawl ' . $url->getPath() . '<br>';
        }
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $crawler = new Crawler($response->getBody());
        if ($this->isProductPage($crawler)) {
            $this->productPageCount += 1;
            // Add product page to the DB table
            $this->storePageInPagesTable($url->getHost().$url->getPath());
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
        $this->storeProductPagesInFurnitureStores();
        if($this->log) {
            echo 'Crawling finished - found ' . $this->productPageCount . ' pages.<br>';
        }
    }

    private function isProductPage(Crawler $crawler): bool
    {
        $basketBtn = $this->getBasketButtons($crawler);
        if ($basketBtn) {
            return true;
        }
        return false;
    }

    private function getBasketButtons(Crawler $crawler)
    {
        // To-do: Loop through Xpath's until one provides a desirable result
        $buttonsFromXPath = $this->getBasketButtonsFromXPath($crawler);
        $buttonsFromAttributes = $this->getBasketButtonsFromAttributes($crawler);
        if (
            ($buttonsFromXPath->getNode(0) && $buttonsFromAttributes->getNode(0))
            || (!$buttonsFromXPath->getNode(0) && $buttonsFromAttributes->getNode(0))
        ) {
            return $buttonsFromAttributes;
        }
        if($buttonsFromXPath->getNode(0) && !$buttonsFromAttributes->getNode(0)) {
            return $buttonsFromXPath;
        }
        return null;
    }

    private function getBasketButtonsFromXPath(Crawler $crawler)
    {
        return $crawler->filterXPath(config('constants.xpath'));
    }

    private function getBasketButtonsFromAttributes(Crawler $crawler)
    {
        return $crawler->filterXPath(createXPathFromAttrList());
    }    

    private function storePageInPagesTable($url)
    {
        if ($this->log) echo 'Attempting to store ' . $url . '<br>';
        if (!$this->productPageRepo->pageExists($url)) {
            echo 'Adding ' . $url . 'to the DB.';
            $this->productPageRepo->addProductPage([
                'url' => $url,
                'furniture_store_id' => $this->furnitureStore->id
            ]);
        } else {
            if ($this->log) echo $url . ' already exists in the database.<br>';
        }   
    }

    private function logProductPage($url)
    {
        echo '<br>';
        echo $url->getPath() . ' is a product page.<br>';
        echo 'Current count: ' . $this->productPageCount . '<br>';
        echo '<br>';
    }

    private function storeProductPagesInFurnitureStores()
    {
        $this->furnitureStoreRepo->setNumProductPages($this->furnitureStore->id, $this->productPageCount);
    }

}