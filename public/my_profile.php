<?php
require_once 'templates/header.php';

if (!isset($_SESSION['Username'])) {
    header("location:index.php");
    exit;
}

if (isset($_SESSION['Username'])) {
    $username = $_SESSION['Username'];
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $age = trim($_POST['age']);
    $email = trim($_POST['email']);

    $errors = [];

    if (empty($name) || !preg_match("/^[a-zA-Z]*$/", $name)) {
        $errors[] = "Name cannot be empty and should contain only letters.";
    }

    if (empty($surname) || !preg_match("/^[a-zA-Z]*$/", $surname)) {
        $errors[] = "Surname cannot be empty and should contain only letters.";
    }

    if (empty($age) || !is_numeric($age) || $age < 18 || $age > 100) {
        $errors[] = "Age must be a number between 18 and 100.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email cannot be empty and should be a valid email address.";
    }

    if (count($errors) === 0) {
        try {
            $sql = "UPDATE users
            SET name = :name,
            surname = :surname,
            age = :age,
            email = :email
            WHERE username = :username";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':name', $name);
            $statement->bindValue(':surname', $surname);
            $statement->bindValue(':age', $age);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }
}

?>
<div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php if (isset($_POST['submit']) && count($errors) === 0): ?>
                    <div class="alert alert-success" role="alert">
                        Your profile has been updated successfully!
                    </div>
                <?php endif; ?>

<?php if (isset($_POST['submit']) && count($errors) > 0): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo implode('<br>', $errors); ?>
    </div>
<?php endif; ?>

                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h2 class="mb-0 bg-secondary">Edit your profile</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($user): ?>
                            <form id="profile-form" method="post" action="my_profile.php">
                                <div class="form-group mb-3">
                                    <label for="name">Name:</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="surname">Surname:</label>
                                    <input type="text" id="surname" name="surname" class="form-control" value="<?php echo htmlspecialchars($user['surname']); ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="age">Age:</label>
                                    <input type="text" id="age" name="age" class="form-control" value="<?php echo htmlspecialchars($user['age']); ?>">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
                                </div>
                                <button class="btn btn-primary" type="submit" name="submit">Save</button>
                            </form>
                        <?php else: ?>
                            <p>You are not logged in.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php require_once 'templates/footer.php'; ?>

