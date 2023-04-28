<?php
session_start();

if (!isset($_SESSION['Username'])) {
    header("location: login.php");
    exit;
}
if (isset($_GET['id'])) {
    $productId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
} else {
    header("location: index.php");
    exit;
}
require_once '../src/DBconnect.php';

try {

    $sql = "UPDATE products SET quantity = quantity - 1 WHERE id = :id AND quantity > 0";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':id', $productId);
    $stmt->execute();

    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }

    if (isset($_SESSION['basket'][$productId])) {
        $_SESSION['basket'][$productId]++;
    } else {
        $_SESSION['basket'][$productId] = 1;
    }

    header("location: index.php");
    exit;
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
