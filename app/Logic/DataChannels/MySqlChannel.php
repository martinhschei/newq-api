<?php

namespace App\Logic\DataChannels;

use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;

class MySqlChannel extends DataChannelBase
{
    private $pdo;

    public function fetch(): IDataChannel
    {
        try {
            if (!$this->connect()) {
                $this->result = new DataChannelResult(null, false, "Database connection failed.", -1);
                return $this;
            }

            $statement = $this->pdo->query($this->channelConfig->databaseQuery ?? "SELECT VERSION() as server_version, NOW() as server_time");

            $rows = [];

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }

            $this->result = new DataChannelResult($rows,true,"Database query ok.",1,"", [
                'content_type' => 'application/json',
            ]);
        }
        catch (PDOException $e) {
            Log::debug("here");
            $this->result = new DataChannelResult(null, false, "Database query failed.", -1, $e->getMessage());
        }
        catch (Exception $e) {
            $this->result = new DataChannelResult(null, false, "Database query failed.", -1, $e->getMessage());
        }

        return $this;
    }

    private function parseConnectionString()
    {
        $urlParts = parse_url($this->uri);

        $host = $urlParts['host'] ?? null;
        $port = $urlParts['port'] ?? 3306;
        $user = $urlParts['user'] ?? null;
        $password = $urlParts['pass'] ?? null;
        $dataBaseName = substr($urlParts['path'], 1);

        return [$host, $port, $user, $password, $dataBaseName];
    }

    public function connect(): array
    {
        list($host, $port, $user, $password, $dataBaseName) = $this->parseConnectionString();
        Log::debug("Connecting to database: {$host}, {$port}, {$user}, {$password}, {$dataBaseName}");
        try {
            $this->pdo = new PDO(
                "mysql:host={$host};port={$port};dbname={$dataBaseName}",
                $user,
                $password,
            );
            return [true, null];
        } catch (PDOException $e) {
            return [false, $e->getMessage()];
        }
    }

    public function persist($data): IDataChannel
    {
        // TODO: Implement persist() method.
    }

    /**
     * @throws \Exception
     */
    public function validate(): IDataChannel
    {
        list($host, $port, $user, $password, $dataBaseName) = $this->parseConnectionString();

        if (is_null($host)) {
            throw new \Exception("Missing host in connection string.");
        }

        if (is_null($dataBaseName)) {
            throw new \Exception("Missing database name in connection string.");
        }

        /*
        if (strlen($this->channelConfig->databaseQuery) === 0) {
            throw new \Exception("Query can not be empty.");
        }

        if (is_null($port)) {
            throw new \Exception("Missing port in connection string.");
        }

        if (is_null($user)) {
            throw new \Exception("Missing user in connection string.");
        }

        if (is_null($password)) {
            throw new \Exception("Missing password in connection string.");
        }
        */

        return $this;
    }
}
