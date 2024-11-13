<?php

namespace App\Logic\DataSources;

use Illuminate\Support\Facades\DB;

class Sqllite
{
    private $connectionString;

    public function __construct($connectionString)
    {
        $this->connectionString = $connectionString;
    }

    public function set()
    {
        config(['database.connections.dynamic_sqlite' => [
            'prefix' => '',
            'driver' => 'sqlite',
            'database' => $this->connectionString,
        ]]);
        
        DB::setDefaultConnection('dynamic_sqlite');
    }

    public function schema()
    {
        $schema = collect(DB::select("SELECT * FROM sqlite_master WHERE tbl_name != 'sqlite_sequence'"))->map(function ($table) {
            return $table->sql;
        });
        
        return [
            'tables' => $schema,
        ];
    }
}