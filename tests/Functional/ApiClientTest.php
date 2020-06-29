<?php

namespace Tests\Functional\Inverse\TuyaClient;

use Inverse\TuyaClient\ApiClient;
use Inverse\TuyaClient\Device\AbstractDevice;
use Inverse\TuyaClient\Device\SwitchDevice;

class ApiClientTest extends BaseTestCase
{
    public function testDiscoverDevices()
    {
        $apiClient = $this->getApiClient();

        $device = $this->getDevice($apiClient);

        if ($device === null) {
            return $this->doesNotPerformAssertions();
        }

        if ($device instanceof SwitchDevice) {
            if (!$device->isOn()) {
                $apiClient->sendEvent($device->getOnEvent());

                $device = $this->getDevice($apiClient);
                $this->assertTrue($device->isOn());
            }

            $apiClient->sendEvent($device->getOffEvent());
            $device = $this->getDevice($apiClient);

            $this->assertFalse($device->isOn());
        }
    }

    private function getDevice(ApiClient $apiClient): ?AbstractDevice
    {
        $devices = $apiClient->discoverDevices();

        $device = null;

        if (!empty($devices)) {
            $device = $devices[0];
        }

        return $device;
    }
}
