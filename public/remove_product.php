<?php
session_start();

if (!isset($_SESSION['Username']) || $_SESSION['UserRole'] !== 'admin') {
    header("location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
} else {
    header("location: index.php");
    exit;
}

require_once '../src/DBconnect.php';

try {
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':id', $productId);
    $stmt->execute();

    header("location: index.php");
    exit;
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}

