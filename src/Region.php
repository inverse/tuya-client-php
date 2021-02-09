<?php

namespace Inverse\TuyaClient;

use Webmozart\Assert\Assert;

class Region
{
    private const CN = 'cn';
    private const EU = 'eu';
    private const US = 'us';

    private const VALID_REGIONS = [
        self::CN,
        self::EU,
        self::US,
    ];

    private $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::VALID_REGIONS, '%s is not a valid region');
        $this->value = $value;
    }

    public static function fromAccessToken(Token $token): self
    {
        $prefix = substr($token->getAccessToken(), 0, 2);
        switch ($prefix) {
            case 'AY':
                $value = self::CN;

                break;
            case 'EU':
                $value = self::EU;

                break;
            case 'US':
            default:
                $value = self::US;

                break;
        }

        return new self($value);
    }

    public function __toString()
    {
        return $this->value;
    }

    public static function createDefault(): self
    {
        return new self(self::US);
    }

    public static function createUS(): self
    {
        return new self(self::US);
    }

    public static function createCN(): self
    {
        return new self(self::CN);
    }

    public static function createEU(): self
    {
        return new self(self::EU);
    }
}
