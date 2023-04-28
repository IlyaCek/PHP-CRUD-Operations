
<?php require_once 'templates/header.php'; ?>
<?php

if (!isset($_SESSION['Username']) || $_SESSION['UserRole'] !== 'admin') {
    header("location: index.php");
    exit;
}

require_once '../common.php';
require_once '../src/DBconnect.php';


if (isset($_POST["submit"])) {
    $username = filter_var($_POST['username'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9_]+$/")));
    $check_duplicate_username = "SELECT COUNT(*) FROM users WHERE username = :username";
    $statement = $connection->prepare($check_duplicate_username);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $username_count = $statement->fetchColumn();

    try {
        $user = array(
            "username" => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
            "name" => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "surname" => filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "age" => filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT),
            "email" => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            "user_role" => filter_input(INPUT_POST, 'user_role', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );


        $sql = "INSERT INTO users (username, password, name, surname, age, email, user_role)
                VALUES (:username, :password, :name, :surname, :age, :email, :user_role)";

        if ($username_count == 0) {
            $statement = $connection->prepare($sql);
            $statement->execute($user);

            echo '<div class="alert alert-success" role="alert">';
            echo htmlspecialchars($user['username']) . ' successfully added';
            echo '</div>';

            header("Refresh: 1; URL=admin.php");
        } else {
            echo '<div class="alert alert-danger" role="alert">';
            echo 'The username is already taken. Please choose a different one.';
            echo '</div>';
        }

    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}


try {
    $sql = "SELECT * FROM users";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

?>

<div class="container mt-5 thead-dark">
    <div class="card">
        <h5 class="card-header bg-secondary text-white">Update Users</h5>
        <div class="card-body">
            <div class="table-responsive ">
                <table class="table table-bordered table-hover thead-dark">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Age</th>
                        <th>User Role</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($result as $row) : ?>
                        <tr>
                            <td><?php echo escape($row["id"]); ?></td>
                            <td><?php echo escape($row["username"]); ?></td>
                            <td><?php echo escape($row["name"]); ?></td>
                            <td><?php echo escape($row["surname"]); ?></td>
                            <td><?php echo escape($row["email"]); ?></td>
                            <td><?php echo escape($row["age"]); ?></td>
                            <td><?php echo ($row["user_role"] == "admin") ? '<span style="color: red;">Admin</span>' : 'User'; ?></td>
                            <?php if ($row["id"] == 1) : ?>
                                <td colspan="2" style="color: red;">Admin profile cannot be edited or deleted</td>
                            <?php else : ?>
                                <td><a href="update_profile.php?id=<?php echo escape($row["id"]); ?>" class="btn btn-primary">Edit</a></td>
                                <td><a href="delete.php?" class="btn btn-danger">Delete</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-secondary">
            <h2 class="text-white">Create New User Profile</h2>
        </div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="name">First Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="surname">Last Name:</label>
                    <input type="text" name="surname" id="surname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" name="age" id="age" class="form-control" min="0" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="user_role">User Role:</label>
                    <select name="user_role" id="user_role" class="form-control" required>
                        <option value="">Select a role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-secondary">
            <h2 class=" text-white">Add New Product</h2>
        </div>
        <div class="card-body">
            <form action="add_product.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Product Description:</label>
                    <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Product Price:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">€</span>
                        </div>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="quantity">Product Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="0" required>
                </div>
                <div class="form-group">
                    <label for="image">Product Image:</label>
                    <div class="custom-file">
                        <input type="file" name="image" id="image" class="custom-file-input" accept="image/*" required>
                        <label class="custom-file-label" for="image">Choose file</label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>
