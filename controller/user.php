<?php

require '../db/connection.php';
require '../db/users.php';
require '../vendor/autoload.php'; // Google API client
session_start();
use Google\Client as Google_Client;

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function register($firstname, $lastname, $email, $password) {
        // Registration logic...
    }

    public function login($email, $password) {
        // Login logic...
    }

    public function googleLogin($id_token) {
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
                $newUserId = $this->user->create($firstname, $lastname, $email, '', $google_id);
                
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
    $userController->register($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password']);
} elseif ($_POST['action'] == 'login') {
    $userController->login($_POST['email'], $_POST['password']);
} elseif ($_POST['action'] == 'google_login' && isset($_POST['id_token'])) {
    $userController->googleLogin($_POST['id_token']);
}

?>
