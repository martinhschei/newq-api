<?php

namespace App\Logic\DataChannels;

use Illuminate\Support\Facades\Log;
use App\Logic\DataChannels\HttpChannel;
use App\Logic\DataChannels\IDataChannel;
use App\Logic\DataChannels\MySqlChannel;

class DataChannel
{
    private $uri;
    private $type;
    private DataChannelConfig $channelConfig;

    public function __construct($uri, $type, $channelConfig = new DataChannelConfig([]))
    {
        $this->uri = $uri;
        $this->type = $type;
        $this->channelConfig = $channelConfig;
        Log::debug($uri);
    }

    public function get(): IDataChannel
    {
        switch($this->type) {
            case 'file':
                return new FileChannel($this->uri, $this->channelConfig);
            case 'mysql':
                return new MySqlChannel($this->uri, $this->channelConfig);
            default:
                throw new \Exception('Invalid data channel type');
        }
    }
}
