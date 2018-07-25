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

    public function getValue(): string
    {
        return $this->value;
    }
}