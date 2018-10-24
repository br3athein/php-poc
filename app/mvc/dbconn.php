<?php

/**
 * Main app entrypoint
 *
 * PHP version 7.2.2
 *
 * @category Generic
 * @package  MyAuth
 * @author   br3athein <br3athein@gmail.com>
 * @license  https://gnu.org/licenses/agpl AGPL-3
 * @release  GIT: 84ba1797862a95b074519f3c56bdbeff1b0de60b
 * @link     https://github.com/br3athein/php-poc
 */

/**
 * Database connector.
 *
 * @return mysqli connection
 */
function establishDatabaseConn()
{
    $conn = new mysqli(
        'db',
        getenv('MYSQL_USER'),
        getenv('MYSQL_PASSWORD'),
        getenv('MYSQL_DATABASE')
    );
    if ($conn->connect_error) {
        // invalid due to lint check
        // die(
        // 'Failed to connect: (' . $conn->connect_errno . ') '
        // . $conn->connect_error
        // );
        return null;
    }
    return $conn;
}