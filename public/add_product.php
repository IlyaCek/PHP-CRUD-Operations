<?php
require_once '../src/DBconnect.php';
session_start();

if (!isset($_SESSION['Username']) || $_SESSION['UserRole'] !== 'admin') {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $image = $_FILES['image'];
    $imageName = time() . '-' . basename($image['name']);
    $imagePath = '../public/images/' . $imageName;

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        try {
            $sql = "INSERT INTO products (name, description, price, quantity, image) VALUES (:name, :description, :price, :quantity, :image)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':image', $imagePath);
            $stmt->execute();

            header("location: admin.php");
            exit;
        } catch (PDOException $error) {
            echo "Error: " . $error->getMessage();
        }
    } else {
        echo "Error uploading image.";
    }
}
