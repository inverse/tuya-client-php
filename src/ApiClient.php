<?php

namespace Inverse\TuyaClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Inverse\TuyaClient\Device\DeviceEvent;
use Inverse\TuyaClient\Device\DeviceFactory;
use Inverse\TuyaClient\Exception\TuyaClientException;
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

    /**
     * @var DeviceFactory
     */
    private $deviceFactory;


    public function __construct(Session $session, Client $client = null)
    {
        $this->session = $session;
        $this->client = $client ?? new Client();
        $this->deviceFactory = new DeviceFactory();
    }

    public function discoverDevices(): array
    {
        $response = $this->request('Discovery', 'discovery');

        $devices = [];
        foreach ($response['payload']['devices'] as $device) {
            $devices[] = $this->deviceFactory->fromArray($device);
        }

        return $devices;
    }

    public function sendEvent(DeviceEvent $event, $namespace = 'control')
    {
        $this->request($event->getAction(), $namespace, $event->getDeviceId(), $event->getPayload());
    }

    private function request(string $name, string $namespace, string $deviceId = null, array $payload = []): array
    {
        if (!$this->session->hasAccessToken()) {
            $this->session->setToken($this->getAccessToken());
        }

        if (!$this->isAccessTokenValid()) {
            $this->session->setToken($this->refreshAccessToken());
        }

        $uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/skill'));

        $header = [
            'name' => $name,
            'namespace' => $namespace,
            'payloadVersion' => 1,
        ];

        $payload['accessToken'] = $this->session->getToken()->getAccessToken();

        if ($deviceId) {
            $payload['devId'] = $deviceId;
        }

        $data = [
            'header' => $header,
            'payload' => $payload,
        ];

        $response = $this->client->post($uri, [
            'json' => $data,
        ]);

        $response = json_decode((string)$response->getBody(), true);

        $this->validate($response, sprintf('Failed to get response from %s', $name));

        return $response;
    }

    private function getAccessToken(): Token
    {
        $uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/auth.do'));

        $response = $this->client->request('POST', $uri, [
            'form_params' => [
                'userName' => $this->session->getUsername(),
                'password' => $this->session->getPassword(),
                'countryCode' => $this->session->getCountryCode(),
                'from' => 'tuya'
            ]
        ]);

        $response = json_decode((string)$response->getBody(), true);

        $this->validate($response, 'An error occurred while fetching access token');

        $token = Token::fromArray($response);

        return $token;
    }

    private function refreshAccessToken(): Token
    {
        $uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/access.do'));

        $response = $this->client->request('GET', $uri, [
            'query' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->session->getToken()->getRefreshToken(),
            ]
        ]);

        $response = json_decode((string)$response->getBody(), true);

        $this->validate($response, 'Failed to refresh access token');

        $token = Token::fromArray($response);

        return $token;
    }

    private function isAccessTokenValid(): bool
    {
        $token = $this->session->getToken();

        return time() + $token->getExpireTime() > time();
    }

    private function getBaseUrl(Session $session): UriInterface
    {
        return new Uri(sprintf(self::BASE_URL_FORMAT, $session->getRegion()));
    }

    private function validate(array $response, string $message = null)
    {
        if (isset($response['responseStatus']) && $response['responseStatus'] === 'error') {
            $message = $message ?? $response['responseMsg'];
            throw new TuyaClientException($message);
        }
    }
}
