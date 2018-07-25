<?php

namespace Inverse\TuyaClient\Device;

class SwitchDevice extends AbstractDevice
{
    use Stateful;

    private const ACTION_NAME = 'turnOnOff';

    public function getOnEvent(): DeviceEvent
    {
        return new DeviceEvent($this->id, self::ACTION_NAME, ['value' => 1]);
    }

    public function getOffEvent(): DeviceEvent
    {
        return new DeviceEvent($this->id, self::ACTION_NAME, ['value' => 0]);
    }
}
