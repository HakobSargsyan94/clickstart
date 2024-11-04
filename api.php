<?php
require 'db.php';

header("Content-Type: application/json");
//var_dump($_SERVER);die;
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH'],'/'));

switch ($method) {
    case 'GET':
        if (isset($request[1])) {
            getUserById($pdo, $request[1]);
        } else {
            getUsers($pdo);
        }
        break;
    case 'POST':
        createUser($pdo);
        break;
    case 'PUT':
        updateUser($pdo, $request[1]);
        break;
    case 'DELETE':
        deleteUser($pdo, $request[1]);
        break;
    default:
        echo json_encode(['error' => 'Unsupported HTTP method']);
        break;
}

function getUsers($pdo) {
    $stmt = $pdo->query("SELECT * FROM users");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

function createUser($pdo) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (validateUserData($data)) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?) ON CONFLICT (email) DO NOTHING;");
        try {
            $stmt->execute([$data['name'], $data['email']]);
            echo json_encode(['status' => 'User created successfully']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'User with this email already exists']);
        }
    } else {
        echo json_encode(['error' => 'Invalid input data']);
    }
}

function updateUser($pdo, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (validateUserData($data)) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['email'], $id]);
        echo json_encode(['status' => 'User updated successfully']);
    } else {
        echo json_encode(['error' => 'Invalid input data']);
    }
}

function deleteUser($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['status' => 'User deleted successfully']);
}

function validateUserData($data) {
    return isset($data['name']) && strlen($data['name']) > 0 && isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL);
}
