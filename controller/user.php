<?php

require '../db/connection.php';
require '../db/users.php';
require '../vendor/autoload.php'; // Google API client
session_start();
use Ulid\Ulid;
use Google\Client as Google_Client;

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register($firstname, $lastname, $email, $password, $confirm_password) {
        //Checking if the password is match
        if($password !== $confirm_password){
            echo json_encode(['status' => 'error', 'message' => 'Password do not match']);
            return;
        }
        
        //Check if the user already exists
        $userExist = $this->user->findByEmail($email);
        if($userExist){
            echo json_encode(['status' => 'error', 'message' => 'User already exist']);

        }else{

            $id = Ulid::generate(true);
            // Hash the password for security

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $this->user->create($id,$firstname,$lastname,$email,$hashedPassword);

            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            // Return success response
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
        }




    }

    public function login($email, $password) {
        $user = $this->user->findByEmail($email);

        if($user && password_verify($password, $user['password'])){
            $_SESSION['email'] = $user['email'];
                // Return success response
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        }else{
             // Invalid credentials
             echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
        }
    }

    public function googleLogin($id_token) {
        $id = Ulid::generate(true);
        // Verify the ID token with Google
        $client = new Google_Client(['client_id' => '806695403781-0igge161g3vhh6ulu6u0f00pbt2tn267.apps.googleusercontent.com']);  // Specify the CLIENT_ID of the app
        $payload = $client->verifyIdToken($id_token);

        if ($payload) {
            // Token is valid, retrieve the user details
            $google_id = $payload['sub'];
            $email = $payload['email'];
            $firstname = $payload['given_name'];
            $lastname = $payload['family_name'];

            // Check if user exists in the database
            $existingUser = $this->user->findByEmail($email);

            if ($existingUser) {
                // User exists, log them in
                $_SESSION['user_id'] = $existingUser['id'];
                $_SESSION['email'] = $existingUser['email'];
                $_SESSION['firstname'] = $existingUser['firstname'];
                $_SESSION['lastname'] = $existingUser['lastname'];

                echo json_encode(['status' => 'user_exists']);  // JSON response to frontend
            } else {
                // User doesn't exist, create new user account
                $newUserId = $this->user->create($id,$firstname, $lastname, $email, '', $google_id);
                
                // Log in the new user
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['email'] = $email;
                $_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;

                echo json_encode(['status' => 'new_user']);  // JSON response for frontend
            }
        } else {
            // Invalid token
            echo json_encode(['status' => 'error', 'message' => 'Invalid Google ID token']);
        }
    }
}

$userController = new UserController();

if ($_POST['action'] == 'register') {
    $userController->register($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'], $_POST['confirm_password']);
} elseif ($_POST['action'] == 'login') {
    $userController->login($_POST['email'], $_POST['password']);
} elseif ($_POST['action'] == 'google_login' && isset($_POST['id_token'])) {
    $userController->googleLogin($_POST['id_token']);
}

?>
