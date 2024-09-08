<?php
require_once 'connection.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Connection();
    }

    public function register($firstname, $lastname, $email, $password) {
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Email already registered.";
        } else {
            $stmt = $this->db->conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_password);
            if ($stmt->execute()) {
                return "Registration successful!";
            } else {
                return "Error: " . $stmt->error;
            }
        }
    }

    public function login($email, $password) {
        $stmt = $this->db->conn->prepare("SELECT password FROM users WHERE email = ?");
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
}
?>
