<?php

namespace Inverse\TuyaClient;

use Webmozart\Assert\Assert;

class Region
{
    const CN = 'cn';
    const EU = 'eu';
    const US = 'us';

    const VALID_REGIONS = [
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
        $value = '';
        switch ($prefix) {
            case 'AY':
                $value = self::CN;
                break;
            case 'EU':
                $value = self::EU;
                break;
            case 'US':
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
}
