<?php

namespace App\Core\Connection;

use App\Core\Config\Config;
use PDO;

class Connection
{
    protected static ?PDO $pdo = null;

    public static function getInstance(): PDO
    {
        $dbname = Config::config('pdo_dbname');
        $host = Config::config('pdo_host');
        $user = Config::config('pdo_user');
        $password = Config::config('pdo_password');

        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:dbname=%s;host=%s',
                $dbname,
                $host
            );

            self::$pdo = new PDO($dsn, $user, $password);
        }

        return self::$pdo;
    }
}
