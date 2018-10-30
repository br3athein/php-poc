<?php

/**
 * Database connector.
 *
 * @return mysqli connection
 */

class DatabaseConnection
{
    public static function establishDatabaseConnection()
    {
        $conn = new mysqli(
            'db',
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD'),
            getenv('MYSQL_DATABASE')
        );
        if ($conn->connect_error) {
            die(
                "Unable to initialize database connection:"
                ." {$conn->connect_error}"
            );
        }
        return $conn;
    }
}
