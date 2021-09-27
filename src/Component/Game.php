<?php

namespace App\Component;

class Game extends AbstractSite implements SiteInterface
{
    private const HEADERS = [
        'authority' => 'www.game.co.uk',
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8',
    ];

    public function hasChanged(): bool
    {
        $response = $this->httpClient->request('GET', $this->getProductUrl(), [
            'headers' => self::HEADERS,
            'max_redirects' => 0,
        ]);

        return !$this->isResponseRedirect($response, '/playstation-5');
    }

    public function getProductUrl(): string
    {
        return 'https://www.game.co.uk/en/playstation-5-console-2826338';
    }
}