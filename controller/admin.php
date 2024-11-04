<?php
include_once '../db/connection.php';
include_once '../model/admin.model.php';
use Ulid\Ulid;

session_start();

class AdminController {
    private $admin;

    public function __construct() {
        $this->admin = new AdminModel();
    }

    public function register($firstname, $lastname, $email, $role) {
        $adminExist = $this->admin->findByEmail($email);
        if ($adminExist) {
            echo json_encode(['status' => 'error', 'message' => 'User Already Exists']);
        } else {
            $id = Ulid::generate(true);
            $this->admin->create($id, $firstname, $lastname, $email, $role);

            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['role'] = $role;

            echo json_encode(['status' => 'success', 'message' => 'Registration Successful']);
        }
    }

    public function login($email, $password) {
        $admin = $this->admin->findByEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['email'] = $admin['email'];
            echo json_encode(['status' => 'success', 'message' => 'Login Successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Email or Password']);
        }
    }
}

$AdminController = new AdminController();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'login') {
        $AdminController->login($_POST['email'], $_POST['password']);
    } elseif ($_POST['action'] == 'register') {
        $AdminController->register($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['role']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No action specified.']);
}

?>
