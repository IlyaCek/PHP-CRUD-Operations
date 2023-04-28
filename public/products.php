<?php

require_once 'templates/header.php';

$isLoggedIn = isset($_SESSION['Username']);


$products = [
    [
        'id' => 1,
        'name' => 'Wireless Earbuds',
        'description' => 'Description for Wireless Earbuds',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/earbuds.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Fitness Tracker',
        'description' => 'Description for Fitness Tracker',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/fitness.jpg'
    ],
    [
        'id' => 3,
        'name' => 'Smart Watch',
        'description' => 'Description for Smart Watch',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/sswatch.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Bluetooth Speaker',
        'description' => 'Description for Bluetooth Speaker',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/speaker.jpg'
    ],
    [
        'id' => 5,
        'name' => 'Virtual Reality Headset',
        'description' => 'Description for Virtual Reality Headset',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/vr.jpg'
    ],
    [
        'id' => 6,
        'name' => 'Wireless Charger',
        'description' => 'Description for Wireless Charger',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/Wireless Charger.jpg'
    ],
    [
        'id' => 7,
        'name' => 'Portable Power Bank',
        'description' => 'Description for Portable Power Bank',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/Portable Power Bank.png'
    ],
    [
        'id' => 8,
        'name' => 'Action Camera',
        'description' => 'Description for Action Camera',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/Action Camera.jpg'
    ],
    [
        'id' => 9,
        'name' => 'Smart Thermostat',
        'description' => 'Description for Smart Thermostat',
        'price' => 'Coming soon!',
        'quantity' => 0,
        'image' => 'images/Smart Thermostat.png'
    ],
];

?>
<div class="container mt-3">
    <h1 class="display-4 mb-4">COMING SOON PRODUCTS</h1>
    <div class="row">
        <?php foreach ($products as $product) : ?>
            <div class="col-md-4">
                <div class="card my-3">
                    <img class="card-img-top" src="<?php echo $product['image'] ?>" >
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text">Price: <?php echo htmlspecialchars($product['price']); ?></p>
                        <p class="card-text">Quantity in stock: <?php echo htmlspecialchars($product['quantity']); ?></p>
                        <p class="text-danger font-weight-bold">Coming soon!</p>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>

