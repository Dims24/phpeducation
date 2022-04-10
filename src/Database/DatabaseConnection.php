<?php

namespace Database;

use PDO;

class DatabaseConnection
{
    protected PDO $connection;

    public function __construct(
        private string $database,
        private string $host = 'localhost',
        private string $port = '5432',
        private string $username = 'postgres',
        private string $password = 'postgres',
    )
    {
        $this->initConnection();
    }


    public function getConnection(): PDO
    {
        return $this->connection;
    }


    public function setConnection(PDO $connection): void
    {
        $this->connection = $connection;
    }

    public function initConnection()
    {
        $test_connection = new PDO(
            $this->getDSN(),
            $this->username,
            $this->password,
//            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        return $this->connection = $test_connection;
    }

    protected function getDSN(): string
    {
        return "pgsql:host={$this->host};port={$this->port};dbname={$this->database};";
    }


}