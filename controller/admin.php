<?php

include_once '../db/connection.php';
include_once '../model/admin.model.php';
use Ulid\Ulid;
session_start();
class AdminController{
    private $admin;


public function __construct()
{
    $this->admin = new AdminModel();
}

public function register($firstname,$lastname,$email,$password,$confirm_pass){

    if($password !== $confirm_pass){
        echo json_encode(['status' => 'error', 'message' => 'Password do not match']);
        return;
    }

    $adminExist = $this->admin->findbyEmail($email);
    if($adminExist){
        echo json_encode(['status' => 'error', 'message' => 'User Already Exist']);
    }else{
        $id = Ulid::generate(true);

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->admin->create($id, $firstname,$lastname,$email,$hashedPassword);

        $_SESSION['id'] = $id;
        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;

    }


}

public function login($email,$password){
    $admin = $this->admin->findbyEmail($email);

    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['email'] = $admin['email'];

      echo  json_encode(['status' => 'success', 'message' => 'Login Successfully']);

    }else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Email or Password']);
    }
}


}




$AdminController = new AdminController();

if($_POST['action'] == 'login'){
    $AdminController->login($_POST['email'], $_POST['password']);
}elseif($_POST['action'] == 'register'){
    $AdminController->register($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['confirm_pass']);
}








?>