<?php
session_start();
require_once '../config.php';

$connection = new PDO($dsn, $username, $password);

/**
 * @param PDO $connection
 * @return mixed
 */
function getUser(PDO $connection)
{
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

if (isset($_POST['Submit'])) {
    $user = getUser($connection);

    if ($user && password_verify($_POST['Password'], $user['password'])) {

        $_SESSION['Username'] = $user['username'];
        $_SESSION['UserRole'] = $user['user_role'];

        header("location: index.php");
        exit;
    } else {
        echo 'Incorrect Username or Password';
    }
}
