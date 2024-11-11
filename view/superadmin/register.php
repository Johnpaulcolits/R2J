<?php
require '../../db/connection.php'; // Include your database connection class
require '../../vendor/autoload.php'; // Include the ULID library (ensure it's installed via Composer)

use Ulid\Ulid;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Generate a ULID for admin_id
    $admin_id = Ulid::generate(true);

    // Create a new database connection
    $connection = new Connection();

    // Prepare the SQL statement
    $stmt = $connection->conn->prepare("INSERT INTO admin (admin_id, firstname, lastname, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $admin_id, $firstname, $lastname, $email, $role);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $connection->conn->close();
}
?>
