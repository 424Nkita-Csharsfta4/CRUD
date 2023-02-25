<?php

header('Content-Type: application/json');

// Подключение к базе данных
$host = 'localhost';
$dbname = 'crud';
$user = 'admin';
$password = 'root';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Обработка запросов
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Получение списка объектов
    $stmt = $db->query('SELECT * FROM objects');
    $objects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($objects);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Создание нового объекта
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $stmt = $db->prepare('INSERT INTO objects (name) VALUES (:name)');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Обновление объекта
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $stmt = $db->prepare('UPDATE objects SET name = :name WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Удаление объекта
    $id = $_GET['id'];
    $stmt = $db->prepare('DELETE FROM objects WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
