<?php
namespace App\Services;

use App\Models\FurnitureItem;
use App\Repository\Eloquent\FurnitureItemRepository;
use DOMNodeList;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class FurnitureDetailsExtractor {

    protected $client;
    protected $furnitureItemRepository;

    public function __construct(FurnitureItemRepository $furnitureItemRepository)
    {
        $this->client = new Client();
    }

    public function testExtraction()
    {
        // $furnitureItem = FurnitureItem::where(['furniture_store_id' => 1])->firstOrFail();
        $furnitureItem = FurnitureItem::find(136);

        $this->extractDetails($furnitureItem);
    }

    public function extractDetails(FurnitureItem $furnitureItem)
    {
        $crawler = $this->getCrawler($furnitureItem->url);

        $title = $this->extractTitle($crawler, $furnitureItem);
        $price = $this->extractPrice($crawler, $furnitureItem);
        $dimensions = $this->extractDimensions($crawler, $furnitureItem);

        return [
            "title" => $title,
            "price" => $price,
            "dimensions" => $dimensions,
        ];
    }

    private function getCrawler(string $url): Crawler
    {
        $response = $this->client->request('GET', $url);
        return new Crawler($response->getBody());
    }

    private function extractTitle(Crawler $crawler, FurnitureItem $furnitureItem)
    {
        $xPath = config('constants.titleXPaths')[$furnitureItem->furnitureStore->id];
        $title = $crawler->filterXPath($xPath)->innerText();
        return $title;
    }

    private function extractPrice(Crawler $crawler, FurnitureItem $furnitureItem)
    {
        $storeId = $furnitureItem->furnitureStore->id;
        $xPath = config('constants.priceXPaths')[$storeId];

        if ($furnitureItem->furnitureStore->id == 1) {
            $ddElement = $crawler->filterXPath($xPath)->getNode(0);
            $priceWithoutFormatting = $ddElement->childNodes->item(1)->nodeValue;
            // ->getNode(0)
            $formattedPrice = $this->formatPrice($priceWithoutFormatting, $storeId);
        }

        return $formattedPrice;
    }

    private function extractDimensions(Crawler $crawler, FurnitureItem $furnitureItem)
    {
        if ($furnitureItem->furnitureStore->id == 1) {
            $section = $crawler->filter('section#details');
            $wrapperDiv = $section->children('div.wrap');
            $accordionWrapDiv = $wrapperDiv->children()->children('div.accordion__wrap'); //->filter('div.accordion__wrap')->eq(1);
            $divFirstChild = $accordionWrapDiv->filter('div')->eq(0);
            $innerText = $divFirstChild->filter('p')->eq(0)->text();

            $formattedDimensions = $this->formatDimensions($innerText, 1);
            $dimensionsInCm = explode('/', $formattedDimensions)[0];
            $individualDimensions = explode('x', $dimensionsInCm);
            return [
                "height" => $individualDimensions[0],
                "width" => $individualDimensions[1],
                "depth" => $individualDimensions[2],
            ];
        }
    }

    private function formatPrice(string $unformattedString, $furnitureStoreId) 
    {
        $cleanedString = preg_replace('/[^\d£$€]+/', '', $unformattedString);
        return $cleanedString;
    }

    private function formatDimensions(string $unformattedString, $furnitureStoreId)
    {
        $cleanedString = preg_replace('#[^0-9/x]+#', '', $unformattedString);
        return $cleanedString;
    }
}