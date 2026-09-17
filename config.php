<?php

$configFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . "config.local.php";

if (!file_exists($configFile)) {
    die("Monitor is not configured. Copy config.local.php.example to config.local.php and set the database password.");
}

require_once $configFile;

function monitor_db()
{
    $conn = new mysqli(
        MONITOR_DB_HOST,
        MONITOR_DB_USER,
        MONITOR_DB_PASSWORD,
        MONITOR_DB_NAME
    );

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

?>
