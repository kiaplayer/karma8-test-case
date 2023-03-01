<?php

require_once('common.php');

$dbHost = getenv('DB_HOST') || '127.0.0.1';
$dbPort = getenv('DB_PORT') || 5432;
$dbName = getenv('DB_NAME') || 'app';
$dbUsername = getenv('DB_USERNAME') || 'postgres';
$dbPassword = getenv('DB_PASSWORD') || ''; // Password can be empty

$dbConnection = pg_connect("host='$dbHost' port='$dbPort' dbname='$dbName' user='$dbUsername' password='$dbPassword'");
if (!$dbConnection) {
    logError('Database connection error');
}

return $dbConnection;
