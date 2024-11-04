<?php
$host = 'PostgreSQL-16'; // Сделано так для ОпенСервера
$db = 'test_task';
$user = 'postgres';
$pass = '';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
