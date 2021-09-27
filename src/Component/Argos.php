<?php

namespace App\Component;

class Argos extends AbstractSite implements SiteInterface
{
    private const HEADERS = [
        'authority' => 'www.argos.co.uk',
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
            'max_redirects' => 0,
        ]);

        return !$this->isResponseRedirect($response, '/vp/oos/ps5.html');
    }

    public function getProductUrl(): string
    {
        return 'https://www.argos.co.uk/product/8349000?clickSR=slp:term:ps5:1:568:1';
    }
}