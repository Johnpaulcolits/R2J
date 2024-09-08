<?php
require_once '../db/users.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    $user = new User();

    if ($action == 'register') {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        echo $user->register($firstname, $lastname, $email, $password);
    } elseif ($action == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        echo $user->login($email, $password);
    }
}
?>
