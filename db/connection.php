<?php
class Connection {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'r2j';
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
?>
