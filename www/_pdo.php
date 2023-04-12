<?php

// connect to database config
$dbhost    = 'db';
$dbname    = 'hub';
$dbuser    = 'account';
$dbpassword  = 'password';

try {
    $db = new PDO(
        "mysql:host={$dbhost};dbname={$dbname};",
        $dbuser, $dbpassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;

} catch(Exception $e) {
    error_log('Connection failed: ' . $e->getMessage());
}
?>