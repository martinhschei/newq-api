<?php

namespace App\Logic\DataChannels;

use App\Logic\Utils\ContentType;
use Illuminate\Support\Facades\Http;

class HttpChannel extends DataChannelBase
{
    public function persist($data): IDataChannel
    {
        $response = Http::post($this->uri, $data);

        if ($response->failed()) {
            $this->result = new DataChannelResult([], false, 'Failed to post data to endpoint', $response->status());
            return $this;
        }

        $this->result = new DataChannelResult($response->json(), true, 'Successfully posted data to endpoint', $response->status());

        return $this;
    }

    public function fetch(): IDataChannel
    {
        $response = Http::get($this->uri);

        if ($response->failed()) {
            $this->result = new DataChannelResult([], false, 'Not able to fetch from data source', $response->status());
            return $this;
        }

        $contentType = $response->header('Content-Type');

        if (str_contains($contentType, ContentType::JSON)) {
            $parsedResponse = $response->json();
        } elseif (str_contains($contentType, ContentType::XMLApplication) || str_contains($contentType, ContentType::XMLText)) {
            $parsedResponse = simplexml_load_string($response->body());
        } elseif (str_contains($contentType, ContentType::HTML)) {
            $parsedResponse = $response->body();
        } else {
            $parsedResponse = $response->body();
        }

        $this->result = new DataChannelResult($parsedResponse, true, 'Successfully fetched data from endpoint', 200, "",
            [
            'content_type' => $contentType,
        ]);

        return $this;
    }

    public function validate(): IDataChannel
    {
        if ($this->uri == null || strlen($this->uri) == 0) {
            throw new \Exception('Uri is required for this data channel');
        }

        return $this;
    }
}
