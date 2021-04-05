<?php

namespace App\Component;

class Argos extends AbstractSite implements SiteInterface
{
    private const HEADERS = [
        'authority' => 'www.argos.co.uk',
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'referer' => 'https://www.argos.co.uk/search/ps5/?clickOrigin=searchbar:home:term:ps5',
        'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8',
    ];

    public function hasChanged(): bool
    {
        $response = $this->httpClient->request('GET', $this->getProductUrl(), [
            'headers' => self::HEADERS,
            'max_redirects' => 0,
        ]);

        return !$this->isResponseRedirect($response, '/vp/oos/ps5.html');
    }

    public function getProductUrl(): string
    {
        return 'https://www.argos.co.uk/product/8349000?clickSR=slp:term:ps5:1:568:1';
    }
}