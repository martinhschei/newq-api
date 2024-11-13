<?php

namespace App\Http\Controllers\Api;

use App\Logic\Utils\Strings;
use Illuminate\Http\Request;
use App\Logic\DataSourceManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Logic\DataChannels\DataChannel;
use App\Http\Resources\DataSourceResource;

class DataSourceController extends Controller
{
    public function connect()
    {
        request()->validate([
            'connection_details' => ['required', 'array'],
            'connection_details.host' => ['required', 'string'],
            'connection_details.port' => ['required', 'integer'],
            'connection_details.username' => ['required', 'string'],
            'connection_details.password' => ['required', 'string'],
            'connection_details.database' => ['required', 'string'],
            'type' => ['required', 'in:mysql,pgsql,mongodb,sqlite'],
        ]);

        $url = Strings::createUrl(
            request('type'),
            request('connection_details.host'),
            request('connection_details.port'),
            request('connection_details.username'),
            request('connection_details.password'),
            request('connection_details.database')
        );
        Log::debug($url);
        $channel = new DataChannel($url, request('type'));
        list($succss, $message) = $channel->get()->connect();

        return [
            'success' => $succss,
            'message' => $message
        ];
    }

    public function register($type)
    {
        $manager = new DataSourceManager($type, request('connection_string'));
        $dataSource = $manager->register();
        return new DataSourceResource($dataSource);
    }
}
