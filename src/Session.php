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
     * @var BizType
     */
    private $bizType;

    /**
     * @var Region
     */
    private $region;

    public function __construct(
        string $username,
        string $password,
        int $countryCode,
        ?BizType $bizType = null,
        ?Region $region = null
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->countryCode = $countryCode;
        $this->bizType = $bizType ?? BizType::createDefault();
        $this->region = $region ?? Region::createDefault();
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

    public function getBizType(): BizType
    {
        return $this->bizType;
    }

    public function setRegion(Region $region): void
    {
        $this->region = $region;
    }
}
