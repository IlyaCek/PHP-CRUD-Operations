<?php

session_start();

if (!isset($_SESSION['Username'])) {
    header("location: " . htmlspecialchars("login.php"));
    exit;
}

require_once '../src/DBconnect.php';

$basket = $_SESSION['basket'] ?? [];
$productIds = array_keys($basket);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));

try {
    $sql = "SELECT * FROM products WHERE id IN ($placeholders)";
    $stmt = $connection->prepare($sql);
    $stmt->execute($productIds);
    $products = $stmt->fetchAll();
} catch (PDOException $error) {

    echo "Error: " . $error->getMessage();
    exit;
}

$totalPrice = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    unset($_SESSION['basket']);
    header("location: " . htmlspecialchars("index.php"));
    exit;
}

require_once 'templates/header.php';
?>

<div class="container">
    <h1>Checkout</h1>
    <?php if (count($products) > 0): ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <?php
                $quantity = $basket[$product['id']];
                $subtotal = $product['price'] * $quantity;
                $totalPrice += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>€<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                    <td><?php echo htmlspecialchars($quantity); ?></td>
                    <td>€<?php echo htmlspecialchars(number_format($subtotal, 2)); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="3">Total Price</th>
                <th>€<?php echo htmlspecialchars(number_format($totalPrice, 2)); ?></th>
            </tr>
            </tfoot>
        </table>
        <form method="POST">

            <button name="Submit" value="Confirm" class="btn btn-dark" type="submit">Confirm</button>
        </form>
    <?php else: ?>
        <p class="alert alert-danger">Your basket is empty.</p>
    <?php endif; ?>
</div>
<?php require "templates/footer.php"; ?>
