<?php

namespace App\Logic\Utils;

use Exception;
use Illuminate\Support\Facades\Log;

class Strings
{
    static function convertToDSN($url) {
        $parts = parse_url($url);
        Log::debug($parts["scheme"]);
        if ($parts === false || !isset($parts['scheme']) || ($parts['scheme'] !== 'mysql' && $parts['scheme'] !== 'postgres')) {
            throw new Exception("Invalid database scheme:". $parts['scheme']);
        }

        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';
        $port = $parts['port'] ?? '3306';
        $host = $parts['host'] ?? '127.0.0.1';
        $database = trim($parts['path'], '/');

        return sprintf(
            "%s:%s@tcp(%s:%s)/%s",
            $user,
            $pass,
            $host,
            $port,
            $database
        );
    }
    
    static function createUrl($scheme, $host, $port, $user, $pass, $database) {
        return sprintf(
            "%s://%s:%s@%s:%s/%s",
            $scheme,
            $user,
            $pass,
            $host,
            $port,
            $database
        );
    }
}
