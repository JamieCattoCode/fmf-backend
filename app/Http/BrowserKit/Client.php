<?php

namespace App\Http\BrowserKit;

use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\Response;

class Client extends AbstractBrowser
{
    protected function doRequest($request): Response 
    {
        return new Response('', 1, ['']);
    }
}