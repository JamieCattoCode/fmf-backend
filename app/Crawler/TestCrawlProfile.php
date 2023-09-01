<?php
namespace App\Crawler;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class TestCrawlProfile extends CrawlProfile
{
    public function shouldCrawl(UriInterface $url): bool
    {
        //str_starts_with($url->getPath(), '/titles/')
        // echo '<br>Current path: ' . $url->getPath();
        // echo '<br>Current scheme: ' . $url->getPath();
        // echo '<br>Current authority: ' . $url->getAuthority();
        // echo '<br>Current host: ' . $url->getHost();
        // echo '<br>Current fragment: ' . $url->getFragment();
        // echo '<br>';
        return true;
    }
}