<?php

namespace App\Logic;

use App\Logic\DataSources\Sqllite;
use Illuminate\Support\Facades\DB;

class DataSourceManager
{
    private $type;
    private $connectionString;

    public function __construct($type, $connectionString)
    {
        $this->type = $type; 
        $this->connectionString = $connectionString;
    }

    public function register()
    {
        if (method_exists($this, $this->type)) {
            return $this->{$this->type}();
        }
    }

    private function sqllite()
    {
        try {
            $dataSource = new Sqllite($this->connectionString);
            $dataSource->set();
            $schema = $dataSource->schema();

            $dataSource = auth()->user()->dataSources()->create([
                'type' => $this->type,
                'schema' => $schema,
                'connection_string' => $this->connectionString,
            ]);

            return $dataSource;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}