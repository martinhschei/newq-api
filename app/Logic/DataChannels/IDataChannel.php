<?php

namespace App\Logic\DataChannels;

interface IDataChannel
{
    function connect(): array;
    function fetch(): IDataChannel;
    function persist($data): mixed;
    function validate(): IDataChannel;
    function result(): DataChannelResult;
}
