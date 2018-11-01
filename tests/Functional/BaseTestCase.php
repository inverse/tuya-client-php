<?php

namespace Tests\Functional\Inverse\TuyaClient;

use Inverse\TuyaClient\ApiClient;
use Inverse\TuyaClient\BizType;
use Inverse\TuyaClient\Session;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function getApiClient(): ApiClient
    {
        return new ApiClient($this->getSessionFromEnv());
    }

    protected function getSessionFromEnv(): Session
    {
        $username = getenv('TUYA_USERNAME');
        $password = getenv('TUYA_PASSWORD');
        $countryCode = getenv('TUYA_COUNTRYCODE');

        return new Session($username, $password, $countryCode, new BizType(BizType::SMART_LIFE));
    }
}
