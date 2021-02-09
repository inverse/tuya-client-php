<?php

namespace Tests\Functional\Inverse\TuyaClient;

use Inverse\TuyaClient\ApiClient;
use Inverse\TuyaClient\Device\AbstractDevice;
use Inverse\TuyaClient\Device\SwitchDevice;

class ApiClientTest extends BaseTestCase
{
    public function testDiscoverDevices(): void
    {
        $apiClient = $this->getApiClient();

        $device = $this->getDevice($apiClient);

        if ($device === null) {
            self::assertNull(null);

            return;
        }

        if ($device instanceof SwitchDevice) {
            if (!$device->isOn()) {
                $apiClient->sendEvent($device->getOnEvent());

                $device = $this->getDevice($apiClient);
                self::assertTrue($device->isOn());
            }

            $apiClient->sendEvent($device->getOffEvent());
            $device = $this->getDevice($apiClient);

            self::assertFalse($device->isOn());
        }
    }

    private function getDevice(ApiClient $apiClient): ?AbstractDevice
    {
        $devices = $apiClient->discoverDevices();

        if (count($devices) === 0) {
            $this->addWarning('No devices returned');
        }

        $device = null;

        if (!empty($devices)) {
            $device = $devices[0];
        }

        return $device;
    }
}
