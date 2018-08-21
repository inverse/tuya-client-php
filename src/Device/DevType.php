<?php

namespace Inverse\TuyaClient\Device;

use Webmozart\Assert\Assert;

class DevType
{
    const SWITCH = 'switch';

    private const DEV_TYPES = [
        self::SWITCH,
    ];

    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::DEV_TYPES);
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
