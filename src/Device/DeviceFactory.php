<?php

namespace Inverse\TuyaClient\Device;

class DeviceFactory
{
    public function fromArray(array $data): ?AbstractDevice
    {
        $device = null;
        switch ($data['dev_type']) {
            case DevType::SWITCH:
                $device = new SwitchDevice(
                    $data['name'],
                    $data['icon'],
                    $data['id'],
                    new DevType($data['dev_type']),
                    $data['ha_type']
                );

                $device->setIsOnline($data['data']['online']);
                $device->setState($data['data']['state']);

                break;
        }

        return $device;
    }
}
