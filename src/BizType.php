<?php

namespace Inverse\TuyaClient;

use Webmozart\Assert\Assert;

class BizType
{
    const TUYA = 'tuya';
    const SMART_LIFE = 'smart_life';

    const VALID_BIZ_TYPES = [
        self::TUYA,
        self::SMART_LIFE,
    ];

    private $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::VALID_BIZ_TYPES, '%s is not a valid BizType');
        $this->value = $value;
    }

    public static function createDefault(): self
    {
        return new self(self::TUYA);
    }

    public function __toString()
    {
        return $this->value;
    }
}
