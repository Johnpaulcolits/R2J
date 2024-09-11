

<?php
require_once 'connection.php';

class User {
    private $conn;

    // Constructor
    public function __construct() {
        $this->conn = (new Connection())->conn;
    }

    // Create User (Google login or standard)
    public function create($firstname, $lastname, $email, $password, $google_id = null) {
        $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, email, password, google_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $email, $password, $google_id);
        $stmt->execute();
    }

    // Check if user exists by email
    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Login function
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                return "Login successful!";
            } else {
                return "Invalid password.";
            }
        } else {
            return "Email not registered.";
        }
    }

    // Register function
    public function register($firstname, $lastname, $email, $password) {
        // Check if the email already exists
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Email already registered.";
        } else {
            // Register new user
            $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_password);
            if ($stmt->execute()) {
                return "Registration successful!";
            } else {
                return "Error: " . $stmt->error;
            }
        }
    }
}



?>









