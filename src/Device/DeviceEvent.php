<?php

namespace Inverse\TuyaClient\Device;

class DeviceEvent
{
    /**
     * @var string
     */
    private $deviceId;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $payload;

    public function __construct(string $deviceId, string $action, array $payload)
    {
        $this->deviceId = $deviceId;
        $this->action = $action;
        $this->payload = $payload;
    }

    public function getDeviceId(): string
    {
        return $this->deviceId;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
