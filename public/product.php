<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['Username']) || $_SESSION['UserRole'] !== 'admin') {
    header("location: index.php");
    exit;
}

if (isset($_POST['add_product'])) {
    require "../common.php";

    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = floatval($_POST['product_price']);
    $product_quantity = $_POST['product_quantity'];

    try {
        $sql = "INSERT INTO products (name, description, price, quantity) VALUES (:name, :description, :price, :quantity)";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':name', $product_name);
        $statement->bindParam(':description', $product_description);
        $statement->bindParam(':price', $product_price);
        $statement->bindParam(':quantity', $product_quantity, PDO::PARAM_INT);
        $statement->execute();
        header("location: admin.php");

        exit;
    } catch (PDOException $error) {
        echo "Error: " . $error->getMessage();
    }
}
