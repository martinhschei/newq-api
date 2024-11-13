<?php

namespace App\Logic\DataChannels;

class DataChannelConfig
{
    private $base = [];

    public function __construct(array $base = [])
    {
        $this->base = $base;
    }

    public function __get($name)
    {
        if (isset($this->base[$name])) {
            return $this->base[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->base[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->base[$name]);
    }

    public function __unset($name)
    {
        unset($this->base[$name]);
    }
}
