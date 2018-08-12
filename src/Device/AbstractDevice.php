<?php

namespace Inverse\TuyaClient\Device;

class AbstractDevice
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var DevType
     */
    protected $devType;

    /**
     * @var string
     */
    protected $haType;

    /**
     * @var bool
     */
    protected $isOnline;

    public function __construct(string $name, string $icon, string $id, DevType $devType, string $haType)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->id = $id;
        $this->devType = $devType;
        $this->haType = $haType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDevType(): string
    {
        return $this->devType;
    }

    public function getHaType(): string
    {
        return $this->haType;
    }

    public function isOnline(): bool
    {
        return $this->isOnline;
    }

    public function setIsOnline(bool $isOnline): void
    {
        $this->isOnline = $isOnline;
    }
}
