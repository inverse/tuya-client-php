<?php

namespace Inverse\TuyaClient;

class Session
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $countryCode;

    /**
     * @var Region
     */
    private $region;

    /**
     * @var Token
     */
    private $token;

    public function __construct(string $username, string $password, int $countryCode, ?Region $region = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->countryCode = $countryCode;
        $this->region = $region ?? new Region(Region::US);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCountryCode(): int
    {
        return $this->countryCode;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function hasAccessToken(): bool
    {
        return $this->token instanceof Token;
    }

    public function getToken(): Token
    {
        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
        $this->region = Region::fromAccessToken($token);
    }
}
