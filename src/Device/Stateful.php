<?php

namespace Inverse\TuyaClient\Device;

trait Stateful
{
    /**
     * @var bool
     */
    protected $state;

    public function isState(): bool
    {
        return $this->state;
    }

    public function setState(bool $state): void
    {
        $this->state = $state;
    }
}
