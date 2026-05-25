<?php

namespace Phpify\Core\Database;

use PDO;

class Database
{
    protected static ?PDO $instance = null;

    public static function connect(array $config): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            self::$instance = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$instance;
    }

    public static function getInstance(): ?PDO
    {
        return self::$instance;
    }
}
