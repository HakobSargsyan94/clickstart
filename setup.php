<?php
$host = 'PostgreSQL-16'; // Сделано так для ОпенСервера
$port = '5432';
$adminUser = 'postgres';   // Имя пользователя с правами на создание БД
$adminPassword = ''; // Пароль для пользователя с правами
$dbName = 'test_task';
$tableName = 'users';

try {
    // Подключаемся к PostgreSQL с правами администратора
    $adminPdo = new PDO("pgsql:host=$host;port=$port", $adminUser, $adminPassword);
    $adminPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Создаем базу данных
    $adminPdo->exec("CREATE DATABASE $dbName");
    echo "Database '$dbName' created successfully.\n";

    // Подключаемся к созданной базе данных
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbName", $adminUser, $adminPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Создаем таблицу users
    $sql = "
    CREATE TABLE IF NOT EXISTS $tableName (
        id SERIAL PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(50) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT NOW()
    )";

    $pdo->exec($sql);
    echo "Table '$tableName' created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

