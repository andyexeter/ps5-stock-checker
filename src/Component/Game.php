<?php

namespace App\Component;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Game implements Site
{
    private const URL = 'https://www.game.co.uk/en/m/playstation-5-console-2826338';
    private const HEADERS = [
        'authority' => 'www.game.co.uk',
        'dnt' => '1',
        'upgrade-insecure-requests' => '1',
        'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'sec-fetch-site' => 'none',
        'sec-fetch-mode' => 'navigate',
        'sec-fetch-user' => '?1',
        'sec-fetch-dest' => 'document',
        'accept-language' => 'en-GB,en-US;q=0.9,en;q=0.8',
    ];

    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function hasChanged(): bool
    {
        $request = $this->httpClient->request('GET', self::URL, [
            'headers' => self::HEADERS,
            'max_redirects' => 0,
        ]);

        if (\in_array($request->getStatusCode(), [301, 302])) {
            if ($redirectUrl = $request->getHeaders(false)['location'][0]) {
                $path = parse_url($redirectUrl, PHP_URL_PATH);

                if ($path === '/playstation-5') {
                    return false;
                }
            }
        }

        return true;
    }

    public function getName(): string
    {
        return 'Game';
    }

    public function getProductUrl(): string
    {
        return self::URL;
    }
}