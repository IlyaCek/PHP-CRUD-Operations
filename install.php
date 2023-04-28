<?php

require "config.php";
try {
    $connection = new PDO("mysql:host=$host", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS my_database";
    $connection->exec($sql);
    $connection->exec("USE my_database");
    $sql = file_get_contents("data/init.sql");
    $connection->exec($sql);

    echo "Database and tables created successfully";
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

