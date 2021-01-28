<?php

namespace App\Component;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function Symfony\Component\String\s;

class Box extends AbstractSite implements Site
{
    private const HEADERS = [
        'authority' => 'www.box.co.uk',
        'cache-control' => 'max-age=0',
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'referer' => 'https://www.box.co.uk/playstation5-register',
        'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8',
    ];

    public function hasChanged(): bool
    {
        $response = $this->httpClient->request('GET', $this->getProductUrl(), [
            'headers' => self::HEADERS,
        ]);

        try {
            $crawler = new Crawler($response->getContent());
            $addToCartButton = $crawler->filter('#divBuyButton div[title="Request Stock Alert"]');
            if ($addToCartButton->count()) {
                $comingSoon = $crawler->filter('p.p-stock');
                if ($comingSoon->count() && s($comingSoon->html())->lower()->trim()->equalsTo('coming soon')) {
                    return false;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getProductUrl(): string
    {
        return 'https://www.box.co.uk/CFI-1015A-Sony-Playstation-5-Console_3199689.html';
    }
}