<?php
namespace App\Services;

use App\Models\ProductPage;
use App\Repository\Eloquent\ProductPageRepository;
use DOMElement;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ProductPageClassifier
{
    private $productPageRepository;
    private $client;

    protected $xPathExpressions = [
        '//section[@id="overview"]', // Soho home
        '//div[@id="main-page-content"]', // Simba sleep
        '//main[@id="maincontent"]', // Oka
        '//div[@class="product"]', // Andrew martin
        '//section[contains(@class, "main-product-section")]' // Urbanara
    ];

    public function __construct(ProductPageRepository $productPageRepository)
    {
        $this->productPageRepository = $productPageRepository;
        $this->client = new Client();
    }

    public function classifyAllProductPages()
    {
        // Get the product pages from the database
        $productPageModels = $this->productPageRepository->getAllProductPages();
        $predictions = [];

        $productPageModels = $productPageModels->splice(0, 50);

        foreach($productPageModels as $productPageModel) {
            $this->classifyProductPage($productPageModel, $predictions);
        }

        dd($predictions);

    }

    public function classifyProductPage(ProductPage $productPage, &$predictions)
    {
        // Get the main content
        $furnitureStore = $productPage->furnitureStore;
        $mainXPath = $this->xPathExpressions[$furnitureStore->id-1];
        $crawler = $this->getCrawler($productPage->url);
        $crawler = $crawler->filterXPath($mainXPath);

        if(!$crawler->getNode(0)) {
            return [$productPage->url => 'NOT A PRODUCT PAGE'];
        }

        $productType = $this->classifyProduct($productPage, $crawler);

        $predictions[$productPage->url] = $productType;
        return [$productPage->url => $productType];
    }

    private function classifyProduct(ProductPage $productPage, Crawler $mainContent)
    {
        // Count all of the keywords
        try {
            $keywordCounts = $this->countAllKeywords(config('constants.furnitureTypes'), $mainContent);
            
        } catch (\Throwable $th) {
            ddd($productPage, $mainContent);
        }

        // See which of the keywords is most prominent
        $prediction = $this->predictProductClassification($keywordCounts);
        
        return $prediction;
    }

    private function extractProductDetails(ProductPage $page)
    {
        $crawler = $this->getCrawler($page->url);
        dd($crawler);
        // Identify title
        // Identify price
        // Identify measurements
    }

    private function getCrawler(string $url): Crawler
    {
        $response = $this->client->request('GET', $url);
        return new Crawler($response->getBody());
    }

    private function getClassNames(Crawler $crawler): array
    {
        $classNames = [];
        $elements = $crawler->filter('*[class]');
            // Loop through each node
        $elements->each(function (Crawler $node) use (&$classNames) {
            $nodeClassName = $node->attr('class');
            $nodeClasses = explode(' ', $nodeClassName);

            // Add the class name to an array
            $classNames = array_merge($classNames, $nodeClasses);
        });
        return $classNames;
    }

    private function countKeywords(array $terms, string $keyword): int
    {
        $count = 0;
        foreach ($terms as $term) {
            if (str_contains(strtolower($term), $keyword)) $count++;
        }
        return $count;
    }

    private function countAllKeywords($keywords, Crawler $mainContent)
    {
        $keywordCounts = [];
        foreach ($keywords as $keyword) {
            $pageWords = explode(' ', $mainContent->text());
            $keywordCounts[$keyword] = [
                'text' => $this->countKeywords($pageWords, $keyword),
                'classes' => $this->countKeywords($this->getClassNames($mainContent), $keyword)
            ];
        }
        return $keywordCounts;
    }

    private function predictProductClassification($keywordCounts)
    {
        $prediction = '';
        $currentBest = 0;

        for ($i = 0; $i < count($keywordCounts); $i++) {
            $keywords = array_keys($keywordCounts);
            $countValues = array_values($keywordCounts);
            $valueToCompare = $countValues[$i]['text'] + $countValues[$i]['classes'];
            if ($valueToCompare > $currentBest) {
                $prediction = $keywords[$i];
                $currentBest = $valueToCompare;
            }
        }

        if ($prediction == '') $prediction = 'Could not classify.';

        return $prediction;
    }

}