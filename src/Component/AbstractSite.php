<?php

namespace App\Component;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AbstractSite
{
    protected HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function isResponseRedirect(ResponseInterface $response, ?string $checkPath = null): bool
    {
        if (\in_array($response->getStatusCode(), [301, 302])) {
            if (func_num_args() < 2) {
                return true;
            }

            if ($redirectUrl = $response->getHeaders(false)['location'][0]) {
                $path = parse_url($redirectUrl, PHP_URL_PATH);

                if ($path === $checkPath) {
                    return true;
                }
            }

            return false;
        }

        return false;
    }

    public function getName(): string
    {
        $parts = explode('\\', get_class($this));
        return $parts[array_key_last($parts)];
    }
}