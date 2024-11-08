<?php

namespace Src\Infrastructure\Persistence;

use PDO;

class DatabaseConnection
{
    public static function getConnection(): PDO
    {
        $dbPath = __DIR__ . '/../../../database/development.sqlite3';
        $db = new PDO("sqlite:$dbPath");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
}
