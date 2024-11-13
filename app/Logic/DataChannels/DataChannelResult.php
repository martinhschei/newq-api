<?php

namespace App\Logic\DataChannels;

class DataChannelResult
{
    public mixed $data;
    public mixed $success;
    public mixed $message;
    public mixed $metadata;
    public mixed $statusCode;
    public mixed $exceptionMessage;

    public function __construct($data =[], $success = false, $message = "", $statusCode = 0, $exceptionMessage = "", $metadata = [])
    {
        $this->data = $data;
        $this->success = $success;
        $this->message = $message;
        $this->metadata = $metadata;
        $this->statusCode = $statusCode;
        $this->exceptionMessage = $exceptionMessage;
    }

    public function getDataSample()
    {
        if ($this->metadata["content_type"] === "application/json") {
            $jsonData = json_encode($this->data);
            if (strlen($jsonData) < 1250) {
                return $jsonData;
            }
            return substr($jsonData, 0, 250) . "..." . substr($jsonData, 500, 750) . "..."  . substr($jsonData, -250);
        }
    }
}
