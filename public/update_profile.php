<?php
require "../common.php";
require "templates/header.php";

$updateSuccess = false;

if (isset($_POST['submit'])) {
    try {
        require_once '../src/DBconnect.php';
        $user = [
            "id" => escape($_POST['id']),
            "name" => escape($_POST['name']),
            "surname" => escape($_POST['surname']),
            "email" => escape($_POST['email']),
            "age" => escape($_POST['age']),
            "user_role" => escape($_POST['user_role'])
        ];
        $sql = "UPDATE users
            SET name = :name,
            surname = :surname,
            age = :age,
            email = :email,
            user_role = :user_role
            WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->execute($user);
        $updateSuccess = true;
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

if (isset($_GET['id'])) {
    try {
        require_once '../src/DBconnect.php';
        $id = $_GET['id'];
        $sql = "SELECT id, name, surname, email, age, user_role, date_added FROM users WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
} else {
    echo "Something went wrong!";
    exit;
}

?>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h2>Edit User</h2>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="id" value="<?php echo escape($user['id'] ?? ''); ?>">
                <?php if ($updateSuccess): ?>
                    <div class="alert alert-success" role="alert">
                        User details have been updated successfully.
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo escape($user['name']); ?>">
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" name="surname" id="surname" class="form-control" value="<?php echo escape($user['surname']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo escape($user['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" class="form-control" value="<?php echo escape($user['age']); ?>">
                </div>
                <div class="form-group">
                    <label for="user_role">User Role:</label>
                    <select name="user_role" id="user_role" class="form-control">
                        <option value="user" <?php echo ($user['user_role'] == 'user') ? 'selected' : ''; ?>>User</option>
                        <option value="admin" <?php echo ($user['user_role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_added">Date Added</label>
                    <input type="text" name="date_added" id="date_added" class="form-control" value="<?php echo escape($user['date_added']); ?>" readonly>
                </div>
                <div class="form-group  justify-content-between">
                    <button type="submit" name="submit" class="btn btn-dark mt-3 ">Submit</button>
                    <a href="admin.php" class="btn btn-muted mt-3 btn-outline-danger">Back to Admin Panel</a>
                </div>
            </form>
        </div>

    </div>
        <?php require "templates/footer.php"; ?>


