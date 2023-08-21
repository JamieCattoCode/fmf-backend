<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ContentCrawler extends Controller
{
    private $client;
    private $url;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false
        ]);
        $this->url = 'https://www.furniturevillage.co.uk/ariana-2-seater-fabric-classic-back-sofa/ZFRSP000000000038817.html?dwvar_ZFRSP000000000038817_color=dapple-chocolate-no-insert';
    }

    public function getSelectionFromDocument(Request $request) 
    {
        $selection = $request->query('selection');

        $response = $this->client->get($this->url);
        $content = $response->getBody()->getContents();

        $crawler = new Crawler($content);

        $data = [];

        switch($selection) {
            case 'title':
                $data = $crawler->filter('h1.product-name')->filter('span[itemprop="name"]')->text();
                break;
            case 'price':
                $data = $crawler->filter('span.price-value')->text();
                break;
            case 'description':
                $data = $crawler->filter('div.product-description')->filter('div[itemprop="description"]')->filter('p')->text();
                break;
            default:
                return response()->json(['status' => 404,'message' => 'No content found.']);
        }
                    
        return response()->json(['status' => 200, 'data' => $data]);
    }
}
