<?php
class Product {
    private $db;

    public function __construct($connection) {
        $this->db = $connection;
    }

    public function insertProduct($id, $name, $size, $type, $category, $price, $quantity, $image) {
        $sql = "INSERT INTO products (id, product_name, product_size, product_type, product_category, product_price, product_quantity, product_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssssds", $id, $name, $size, $type, $category, $price, $quantity, $image);
        return $stmt->execute();
    }

    public function getAllProducts() {
        $sql = "SELECT * FROM products";
        $result = $this->db->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }
}
?>
