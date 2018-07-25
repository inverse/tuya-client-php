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

    public function __construct(string $username, string $password, int $countryCode)
    {
        $this->username = $username;
        $this->password = $password;
        $this->countryCode = $countryCode;
        $this->region = new Region(Region::US);
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
}
