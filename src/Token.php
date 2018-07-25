<?php

namespace Inverse\TuyaClient;

class Token
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var int
     */
    private $expireTime;

    public function __construct(string $accessToken, string $refreshToken, int $expireTime)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->expireTime = $expireTime;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getExpireTime(): int
    {
        return $this->expireTime;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['access_token'],
            $data['refresh_token'],
            $data['expires_in']
        );
    }
}
