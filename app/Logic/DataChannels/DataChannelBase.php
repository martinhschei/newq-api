<?php

namespace App\Logic\DataChannels;

abstract class DataChannelBase implements IDataChannel
{
    protected string $uri;
    protected DataChannelResult $result;
    protected DataChannelConfig $channelConfig;

    public function __construct(string $uri, DataChannelConfig $channelConfig = new DataChannelConfig([]))
    {
        $this->uri = $uri;
        $this->channelConfig = $channelConfig;
        $this->result = new DataChannelResult();
    }

    public function result(): DataChannelResult
    {
        return $this->result;
    }

    abstract public function fetch(): IDataChannel;
    abstract public function validate(): IDataChannel;
    abstract public function persist($data): IDataChannel;
}
