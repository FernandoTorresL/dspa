<?php

$dbHost = 'localhost';
$dbName = 'dd_dspa_new_web';
$dbUser = 'dd_dspa_web_user';
$dbPass = 'fabric-widen-rhizome';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    echo $e->getMessage();
}