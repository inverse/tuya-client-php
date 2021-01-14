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

    /**
     * @var TokenManager
     */
    private $tokenManager;

    public function __construct(Session $session, Client $client = null)
    {
        $this->session = $session;
        $this->client = $client ?? new Client();
        $this->deviceFactory = new DeviceFactory();
        $this->tokenManager = new TokenManager();
    }

    public function discoverDevices(): array
    {
        $response = $this->request('Discovery', 'discovery');

        $devices = [];
        foreach ($response['payload']['devices'] as $device) {
            $deviceData = $this->deviceFactory->fromArray($device);
            if ($deviceData) {
                $devices[] = $deviceData;
            }
        }

        return $devices;
    }

    public function sendEvent(DeviceEvent $event, string $namespace = 'control'): void
    {
        $payload = $event->getPayload();
        $payload['devId'] = $event->getDeviceId();
        $this->request($event->getAction(), $namespace, $payload);
    }

    private function checkAccessToken()
    {
        if (!$this->tokenManager->hasToken()) {
            $token = $this->getAccessToken();
            $this->tokenManager->setToken($token);
            $this->session->setRegion(Region::fromAccessToken($token));
        }

        if (!$this->tokenManager->isValidToken()) {
            $this->tokenManager->setToken($this->refreshAccessToken());
        }
    }

    private function request(string $name, string $namespace, array $payload = []): array
    {
        $this->checkAccessToken();

        $uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/skill'));

        $header = [
            'name'           => $name,
            'namespace'      => $namespace,
            'payloadVersion' => 1,
        ];

        $payload['accessToken'] = $this->tokenManager->getToken()->getAccessToken();

        $data = [
            'header'  => $header,
            'payload' => $payload,
        ];

        $response = $this->client->post(
            $uri,
            [
                'json' => $data,
            ]
        );

        $response = json_decode((string) $response->getBody(), true);

        $this->validate($response, sprintf('Failed to get response from %s', $name));

        return $response;
    }

    private function getAccessToken(): Token
    {
        $uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/auth.do'));

        $response = $this->client->post(
            $uri,
            [
                'form_params' => [
                    'userName'    => $this->session->getUsername(),
                    'password'    => $this->session->getPassword(),
                    'countryCode' => $this->session->getCountryCode(),
                    'bizType'     => $this->session->getBizType(),
                    'from'        => 'tuya',
                ],
            ]
        );

        $response = json_decode((string) $response->getBody(), true);

        $this->validate($response, 'An error occurred while fetching access token');

        return Token::fromArray($response);
    }

    private function refreshAccessToken(): Token
    {
        $uri = UriResolver::resolve($this->getBaseUrl($this->session), new Uri('homeassistant/access.do'));

        $response = $this->client->get(
            $uri,
            [
                'query' => [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $this->tokenManager->getToken()->getRefreshToken(),
                ],
            ]
        );

        $response = json_decode((string) $response->getBody(), true);

        $this->validate($response, 'Failed to refresh access token');

        return Token::fromArray($response);
    }

    private function getBaseUrl(Session $session): UriInterface
    {
        return new Uri(sprintf(self::BASE_URL_FORMAT, $session->getRegion()));
    }

    /**
     * @param array  $response
     * @param string $message
     *
     * @throws TuyaClientException
     */
    private function validate(array $response, string $message)
    {
        if (isset($response['responseStatus']) && $response['responseStatus'] === 'error') {
            $message = sprintf('%s - %s', $message, $response['errorMsg']);

            throw new TuyaClientException($message);
        }
    }
}
