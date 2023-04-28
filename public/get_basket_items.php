<?php

session_start();
require_once '../src/DBconnect.php';

if (!isset($_SESSION['Username'])) {
    echo json_encode([]);
    exit;
}

$productIds = array_keys($_SESSION['basket'] ?? []);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

try {
    $sql = "SELECT * FROM products WHERE id IN ($placeholders)";
    $stmt = $connection->prepare($sql);
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode([
        'basket' => $_SESSION['basket'],
        'products' => $products
    ]);


    $_SESSION['products'] = $products;

} catch (PDOException $error) {
    echo json_encode(["error" => "Error: " . $error->getMessage()]);
}
?>
