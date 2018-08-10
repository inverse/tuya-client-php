<?php

namespace Tests\Functional\Inverse\TuyaClient;

use Inverse\TuyaClient\ApiClient;
use Inverse\TuyaClient\Device\SwitchDevice;

class ApiClientTest extends BaseTestCase
{
    public function testDiscoverDevices()
    {
        $apiClient = $this->getApiClient();

        $switch = $this->getDevice($apiClient);

        if (!$switch->isOn()) {
            $apiClient->sendEvent($switch->getOnEvent());

            $switch = $this->getDevice($apiClient);
            $this->assertTrue($switch->isOn());
        }

        $apiClient->sendEvent($switch->getOffEvent());
        $switch = $this->getDevice($apiClient);

        $this->assertFalse($switch->isOn());
    }

    private function getDevice(ApiClient $apiClient): SwitchDevice
    {
        $devices = $apiClient->discoverDevices();
        return $devices[1];
    }
}
