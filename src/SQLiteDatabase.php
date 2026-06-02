<?php

class SQLiteDatabase {
    private static ?PDO $databaseConnection = null;

    private function __construct() {}

    public static function getConnection(): PDO {
        if (null === self::$databaseConnection) {
            self::$databaseConnection = new PDO('sqlite:../database.sqlite3');
        }

        return self::$databaseConnection;
    }
}

?>