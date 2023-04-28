<?php

session_start();

if (!isset($_SESSION['Username']) || $_SESSION['UserRole'] !== 'admin') {
    header("location: index.php");
    exit;
}

require_once '../common.php';
require_once '../src/DBconnect.php';


if (isset($_POST["submit"])) {
    $username = filter_var($_POST['username'], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9_]+$/")));

    if ($username && !empty($_POST['password']) && !empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['age']) && !empty($_POST['email']) && !empty($_POST['user_role'])) {
        try {
            $user = array(
                "username" => $username,
                "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
                "name" => htmlspecialchars($_POST["name"]),
                "surname" => htmlspecialchars($_POST["surname"]),
                "age" => htmlspecialchars($_POST["age"]),
                "email" => htmlspecialchars($_POST["email"]),
                "user_role" => htmlspecialchars($_POST["user_role"])
            );

            $sql = "INSERT INTO users (username, password, name, surname, age, email, user_role)
                    VALUES (:username, :password, :name, :surname, :age, :email, :user_role)";

            $statement = $connection->prepare($sql);
            $statement->execute($user);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $error) {

            echo "Error: " . $error->getMessage();
        }
    } else {

        echo "Error: Invalid input. Please make sure all fields are filled out and valid.";
    }
}

try {
    $sql = "SELECT * FROM users WHERE id != 1";
    $statement = $connection->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();
} catch (PDOException $error) {

    echo "Error: " . $error->getMessage();
}

?>
