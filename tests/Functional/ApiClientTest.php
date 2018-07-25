<?php

namespace Tests\Functional\Inverse\TuyaClient;

use Inverse\TuyaClient\Device\SwitchDevice;

class ApiClientTest extends BaseTestCase
{
    public function testDiscoverDevices()
    {
        $apiClient = $this->getApiClient();
        $devices = $apiClient->discoverDevices();

        /** @var SwitchDevice $switch */
        $switch = $devices[1];

        $apiClient->sendEvent($switch->getOnEvent());

        $devices = $apiClient->discoverDevices();

        /** @var SwitchDevice $switch */
        $switch = $devices[1];

        $apiClient->sendEvent($switch->getOffEvent());
    }
}
