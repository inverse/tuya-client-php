<?php

namespace Tests\Inverse\TuyaClient;

use Inverse\TuyaClient\Session;
use PHPUnit\Framework\TestCase;

class ApiClientTest extends TestCase
{
    private function getSessionFromEnv(): Session
    {
        $username = getenv('TUYA_USERNAME');
        $password = getenv('TUYA_PASSWORD');
        $countryCode = getenv('TUYA_COUNTRYCODE');
        return new Session($username, $password, $countryCode);
    }
}
