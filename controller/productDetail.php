<?php




require_once '../db/connection.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $conn = new Connection();

    $stmt = $conn->conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
    $conn->conn->close();
} else {
    echo json_encode(["error" => "No product ID provided"]);
}
?>








