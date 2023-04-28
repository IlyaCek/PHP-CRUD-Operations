<?php
session_start();
require_once 'templates/header.php';
require_once '../src/DBconnect.php';

try {
    $sql = "SELECT * FROM products";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $products = $statement->fetchAll();
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}

$isLoggedIn = isset($_SESSION['Username']);

?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 mb-4">NEW PRODUCTS</h1>
        </div>
    </div>
    <div class="row">
        <?php foreach ($products as $product) : ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img class="card-img-top" src="<?php echo $product['image'] ?>"  alt="">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title font-weight-bold text-primary"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text font-weight-normal"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text font-weight-bold text-success">Price: €<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                        <p class="card-text font-weight-bold text-success">Quantity in stock: <?php echo htmlspecialchars($product['quantity']); ?></p>
                        <div class="mt-auto">
                            <?php if ($isLoggedIn) : ?>
                                <a href="add_to_basket.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-dark">Add to Basket</a>
                            <?php else : ?>
                                <p>Please <a class="btn btn-dark btn-sm" href="register.php">register</a> and login to add items to your basket.</p>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['UserRole']) && $_SESSION['UserRole'] === 'admin') : ?>
                                <a href="remove_product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="btn btn-danger">Remove Product</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require "templates/footer.php"; ?>
