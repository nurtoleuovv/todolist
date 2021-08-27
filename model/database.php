<?php
    $dsn = 'mysql:host=localhost;dbname=todolistprod';
    $username = 'root';
    //$password = 'Passw0rd!';

    try {
        $db = new PDO($dsn, $username);
        //$db = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        $error = "Database Error: ";
        $error .= $e->getMessage();
        include('view/error.php');
        exit();
    }