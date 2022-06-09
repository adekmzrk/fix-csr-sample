<?php 
include "functions.php";
session_start();
if (!isset($_SESSION["token"]) || !isset($_POST["token"])) {
    exit("token belum di setting!");
}

if (array_diff($_POST['token'], $_SESSION['token'])  ==  array_diff($_SESSION['token'], $_POST['token'])) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        
        function sanitize($data){
            $data = htmlspecialchars($data);
            $data = stripslashes($data);
            $data = trim($data);
            return $data;
        }
        $user = sanitize($_POST['username']);
        $pass = sanitize($_POST['password']);

        //print_r($_SESSION['token'] . "\n");
        //print_r($_POST['token']);

        $pdo = pdo_connect();
        $stmt = $pdo->prepare('SELECT salt FROM users WHERE username = ? LIMIT 1'); 
        $stmt->execute([$user]);
        $saltx = $stmt->fetch(PDO::FETCH_ASSOC);
        $salt = implode(',', $saltx);

        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1'); 
        $stmt->execute([$user, hash('sha256', $pass . $salt)]);

        $notif = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $_SESSION['user'] = $user;
            header("location: index.php");
        } else {
            $notif = "Wrong usename or password";
        }
    }
    
} else {
    exit("token not same!!!");
}


?>    