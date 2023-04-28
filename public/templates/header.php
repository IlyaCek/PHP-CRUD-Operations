
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

$connection = new PDO($dsn, $username, $password);

/**
 * @param $user
 * @return void
 */
function extracted($user)
{
    $_SESSION['Username'] = $user['username'];
    $_SESSION['UserRole'] = $user['user_role'];
    $_SESSION['Active'] = true;
    if ($user['user_role'] == 'admin') {
        header("location: admin.php");
    } else {
        header("location: index.php");
    }
    exit;
}

if (isset($_POST['Submit'])) {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        extracted($user);
    } else {
        $login_error = true;
    }
}

?>
<?php if (isset($login_error)): ?>
    <div class="container mt-3">
        <div class="alert alert-danger" role="alert">
            Incorrect Username or Password
        </div>
    </div>

<?php endif; ?>

<nav class="navbar">

    <div class="brand">
        <a href="index.php">E-SHOP</a>
    </div>
    <ul class="menu">
        <li><a href="index.php">HOME</a></li>
        <li><a href="products.php">COMING SOON</a></li>
        <li><a href="about.php">ABOUT</a></li>
    </ul>
    <div class="auth">
        <?php if (isset($_SESSION['Username'])): ?>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="basketDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge badge-pill badge-danger">
            <?= htmlspecialchars(count($_SESSION['basket'] ?? [])) ?>
        </span>
                </button>
                <div class="dropdown-menu" aria-labelledby="basketDropdown" id="basketDropdownContent">

                </div>
            </div>

        <?php endif; ?>

        <?php if (isset($_SESSION['Username'])): ?>
            <div class="welcome-message d-inline-block text-warning">
                Welcome,
                <span class="font-weight-bold h4"><?= htmlspecialchars($_SESSION['Username']) ?></span>
                | <?= gmdate('F j, Y,  g:i a') ?>
            </div>
            <?php if ($_SESSION['UserRole'] == 'admin'): ?>
                <form action="admin.php" method="GET">
                    <button class="auth-button" type="submit">Admin Panel</button>
                </form>
            <?php else: ?>
                <form action="my_profile.php" method="GET">
                    <button class="auth-button" type="submit">Profile</button>
                </form>
            <?php endif; ?>
            <form action="logout.php" method="POST">
                <button name="Submit" value="Logout" class="auth-button" type="submit">Logout</button>
            </form>

        <?php else: ?>


            <form method="POST">
                <label for="inputUsername"></label><input name="Username" type="username" id="inputUsername" class="input" placeholder="Username" required autofocus>
                <label for="inputPassword"></label><input name="Password" type="password" id="inputPassword" class="input" placeholder="Password" required>
                <button name="Submit" value="Login" class="login-btn" type="submit">Login</button>
            </form>
            <form action="register.php" method="GET">
                <button type="submit" class="register-btn">Register</button>
            </form>
        <?php endif; ?>
    </div>
</nav>
<script>
    function updateBasketDropdown() {
        $.ajax({
            url: 'get_basket_items.php',
            dataType: 'json',
            success: function(response) {
                const basket = response.basket;
                const products = response.products;
                let basketDropdownContent = '';

                if (products.length) {
                    products.forEach(function(product) {
                        const quantity = basket[product.id] || 0;
                        const item = `
                        <div class="dropdown-item d-flex justify-content-between align-items-center">
                            <span>${product.name}</span>
                            <span class="badge badge-primary badge-pill">Quantity: ${quantity}</span>
                        </div>
                    `;
                        basketDropdownContent += item;
                    });
                    basketDropdownContent += '<div class="dropdown-divider"></div>';
                    basketDropdownContent += '<a class="dropdown-item text-center" href="checkout.php"><button class="btn btn-dark">Checkout</button></a>';
                } else {
                    basketDropdownContent += '<a class="dropdown-item text-center" href="#">No items in the basket</a>';
                }

                $('#basketDropdownContent').html(basketDropdownContent);
            }
        });
    }
    $(document).ready(updateBasketDropdown);
</script>




</body>
</html>

