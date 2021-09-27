<?php

namespace App\Component;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Smyths extends AbstractSite implements SiteInterface
{
    private const HEADERS = [
        'authority' => 'www.smythstoys.com',
        'pragma' => 'no-cache',
        'cache-control' => 'no-cache',
        'sec-ch-ua' => '"Chromium";v="94", "Google Chrome";v="94", ";Not A Brand";v="99"',
        'sec-ch-ua-mobile' => '?0',
        'sec-ch-ua-platform' => '"Linux"',
        'upgrade-insecure-requests' => '1',
        'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.54 Safari/537.36',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'sec-fetch-site' => 'none',
        'sec-fetch-mode' => 'navigate',
        'sec-fetch-user' => '?1',
        'sec-fetch-dest' => 'document',
        'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8',
    ];

    public function hasChanged(): bool
    {
        $response = $this->httpClient->request('GET', $this->getProductUrl(), [
            'headers' => self::HEADERS,
        ]);

        try {
            $crawler = new Crawler($response->getContent());
            $addToCartButton = $crawler->filter('#addToCartButton');
            if ($addToCartButton->attr('disabled') === 'disabled') {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getProductUrl(): string
    {
        return 'https://www.smythstoys.com/uk/en-gb/video-games-and-tablets/playstation-5/playstation-5-consoles/playstation-5-console/p/191259';
    }
}