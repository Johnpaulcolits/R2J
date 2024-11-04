<?php
require_once '../db/connection.php'; // Update the path as necessary

class ProductController {
    private $connection;

    public function __construct() {
        // Instantiate the Connection class
        $this->connection = new Connection();
    }

    public function getProductsByCategory() {
        // Query to select one product per category
        $query = "
            SELECT product_category, 
                   MIN(product_name) AS product_name, 
                   MIN(product_image) AS product_image
            FROM products 
            GROUP BY product_category
        ";

        // Execute the query
        $result = $this->connection->conn->query($query);
        $products = [];

        // Fetch the results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        // Return results as JSON
        return json_encode($products);
    }
}

// Create a new instance of ProductController and output the results
$productController = new ProductController();
header('Content-Type: application/json');
echo $productController->getProductsByCategory();
?>
