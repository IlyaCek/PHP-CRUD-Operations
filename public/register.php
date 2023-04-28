<?php require_once 'templates/header.php'; ?>

<?php

if (isset($_POST['submit'])) {
    require "../common.php";
    try {
        require_once '../src/DBconnect.php';

        $errors = [];


        $name = trim($_POST['name']);
        if (empty($name) || !preg_match("/^[a-zA-Z ]*$/", $name)) {
            $errors[] = "Name must contain only letters and not be blank";
        }

        $surname = trim($_POST['surname']);
        if (empty($surname) || !preg_match("/^[a-zA-Z ]*$/", $surname)) {
            $errors[] = "Surname must contain only letters and not be blank";
        }

        $age = $_POST['age'];
        if (empty($age) || !is_numeric($age) || $age < 18 || $age > 100) {
            $errors[] = "Age must be a number between 18 and 100.";
        }

        $email = $_POST['email'];
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email cannot be empty and should be a valid email address.";
        }

if (empty($errors)) {

    $username = filter_var($_POST['username'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9_]+$/")));
    $check_duplicate_username = "SELECT COUNT(*) FROM users WHERE username = :username";
    $statement = $connection->prepare($check_duplicate_username);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $username_count = $statement->fetchColumn();

    if ($username_count == 0) {
        $new_user = array(
            "username" => $username,
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
            "name" => $name,
            "surname" => $surname,
            "age" => $age,
            "email" => $email
        );

        $sql = sprintf(
            "INSERT INTO %s (%s) values (%s)",
            "users",
            implode(", ", array_keys($new_user)),
            ":" . implode(", :", array_keys($new_user))
        );

        $statement = $connection->prepare($sql);
        $statement->execute($new_user);

        if ($statement) {
            echo '<div class="alert alert-success" role="alert">';
            echo htmlspecialchars($new_user['username']) . ' successfully added';
            echo '</div>';

            header("Refresh: 1; URL=index.php");
        }
    } else {
        $errors[] = "The username is already taken. Please choose a different one.";
    }
}
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
}

if (!empty($errors)) {
    echo '<div class="alert alert-danger" role="alert">';
    echo 'There were errors in the submitted data:<br>';
    foreach ($errors as $error) {
        echo htmlspecialchars($error) . '<br>';
    }
    echo '</div>';
}

?>
<div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header  text-white bg-secondary">
                        <h2 class="mb-0 ">Register a new profile</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group mb-3">
                                <label for="username">User name</label>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="surname">Surname</label>
                                <input type="text" name="surname" id="surname" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="age">Age</label>
                                <input type="number" name="age" id="age" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'templates/footer.php'; ?>

