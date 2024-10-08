    <?php


    class User {
        private $conn;

        public function __construct() {
            $this->conn = (new Connection())->conn;
        }

        public function create($id,$firstname, $lastname, $email, $password, $google_id = null) {
            $stmt = $this->conn->prepare("INSERT INTO users (id, firstname, lastname, email, password, google_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $id,$firstname, $lastname, $email, $password, $google_id);
            $stmt->execute();
        }

        public function findByEmail($email) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        
        public function login($email,$password){
            $state = $this->conn->prepare("SELECT * FROM users WHERE email=? and password=?");
            $state->bind_param("ss",$email,$password);
            $state->execute();
            return $state->get_result()->fetch_assoc();
        }
    }

    ?>
