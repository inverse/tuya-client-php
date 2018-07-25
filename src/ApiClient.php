<?php

namespace Inverse\TuyaClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Psr\Http\Message\UriInterface;

class ApiClient
{
    private const BASE_URL_FORMAT = 'https://px1.tuya%s.com';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session, Client $client = null)
    {
        $this->session = $session;
        $this->client = $client ?? new Client();
    }

    public function getAccessToken()
    {
        $baseUrl = $this->getBaseUrl($this->session);

        $uri = UriResolver::resolve($baseUrl, new Uri('homeassistant/auth.do'));

        $response = $this->client->request('POST', $uri, [
            'form_params' => [
                'userName' => $this->session->getUsername(),
                'password' => $this->session->getPassword(),
                'countryCode' => $this->session->getCountryCode(),
                'from' => 'tuya'
            ]
        ]);

        $response = json_decode((string)$response->getBody(), true);

        return $response;
    }

    private function getBaseUrl(Session $session): UriInterface
    {
        return new Uri(sprintf(self::BASE_URL_FORMAT, $session->getRegion()->getValue()));
    }
}