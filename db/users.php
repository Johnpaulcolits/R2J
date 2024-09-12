<?php


class User {
    private $conn;

    public function __construct() {
        $this->conn = (new Connection())->conn;
    }

    public function create($firstname, $lastname, $email, $password, $google_id = null) {
        $stmt = $this->conn->prepare("INSERT INTO users (firstname, lastname, email, password, google_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $email, $password, $google_id);
        $stmt->execute();
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
