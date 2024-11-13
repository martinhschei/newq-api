<?php

namespace App\Logic\DataChannels;

use Illuminate\Support\Facades\File;
use App\Logic\DataChannels\DataChannelConfig;

class FileChannel implements IDataChannel
{
    private $uri;
    private $result;
    private DataChannelConfig $channelConfig;

    public function __construct($uri, DataChannelConfig $channelConfig)
    {
        $this->uri = $uri;
        $this->channelConfig = $channelConfig;
    }

    public function result(): DataChannelResult
    {
        return $this->result;
    }

    public function persist($data): IDataChannel
    {
        try {
            File::put($this->uri, $data);
            $this->result = new DataChannelResult([], true, 'Successfully persisted data to file', 0);
            return $this;
        } catch (\Exception $e) {
            $this->result = new DataChannelResult([], false, 'Failed to persist data to file', -1);
            return $this;
        }
    }

    public function read(): IDataChannel
    {
        try {
            $content = File::get($this->uri);
            $this->result = new DataChannelResult($content, true, 'Successfully fetched data from file', 200);
            return $this;
        } catch (\Exception $e) {
            $this->result = new DataChannelResult([], false, 'Failed to fetch data from file', 500);
            return $this;
        }
    }
}
