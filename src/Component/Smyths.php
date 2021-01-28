<?php

namespace App\Component;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Smyths extends AbstractSite implements Site
{
    private const HEADERS = [
        'authority' => 'www.smythstoys.com',
        'cache-control' => 'max-age=0',
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'referer' => 'https://www.smythstoys.com/uk/en-gb/video-games-and-tablets/playstation-5/c/SM060461',
        'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8',
    ];

    public function hasChanged(): bool
    {
        $request = $this->httpClient->request('GET', $this->getProductUrl(), [
            'headers' => self::HEADERS,
        ]);

        try {
            $crawler = new Crawler($request->getContent());
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