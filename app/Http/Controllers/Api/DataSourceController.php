<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Logic\DataSourceManager;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\DataSourceResource;

class DataSourceController extends Controller
{
    public function register($type)
    {
        $manager = new DataSourceManager($type, request('connection_string'));
        $dataSource = $manager->register();
        return new DataSourceResource($dataSource);
    }
}
