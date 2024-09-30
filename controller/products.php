<?php
require_once '../db/connection.php';
require_once '../model/product.model.php';
require_once '../vendor/autoload.php'; // Assuming ULID is installed via Composer

use Ulid\Ulid;

// Database connection
$connection = new Connection();
$productModel = new Product($connection->conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $productId = Ulid::generate(); // ULID for ID
    $productName = $_POST['product_name'];
    $productSize = $_POST['product_size'];
    $productType = $_POST['product_type'];
    $productCategory = $_POST['product_category'];
    $productPrice = $_POST['product_price'];
    $productQuantity = $_POST['product_quantity'];

    // Handle file upload for image
    $targetDir = "../src/uploads/";
    $fileName = basename($_FILES["product_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if the image is valid
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFilePath)) {
            $image = $targetFilePath; // Store image path in the database

            $success = $productModel->insertProduct($productId, $productName, $productSize, $productType, $productCategory, $productPrice, $productQuantity, $image);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid image file']);
    }
} else {
    // Handle fetching data
    $products = $productModel->getAllProducts();
    echo json_encode($products);
}



?>
