<?php



class AdminModel{

   private  $conn;

  public function __construct()
  {
    $this->conn = (new Connection())->conn;
  }


public function login($email,$password){
    $statement = $this->conn->prepare("SELECT * FROM admin WHERE email=? and  password=?");
    $statement->bind_param("ss",$email,$password);
    $statement->execute();
    return $statement->get_result()->fetch_assoc();

}

public function findbyEmail($email){
$statement = $this->conn->prepare("SELECT * FROM admin WHERE email=?");
$statement->bind_param("s",$email);
$statement->execute();
return $statement->get_result()->fetch_assoc();
}

public function create($id,$firstname,$lastname,$email,$password){
      $create = $this->conn->prepare("INSERT INTO admin (id,firstname,lastname,email,password) VALUES(?, ?, ?, ?, ?)");
      $create->bind_param("sssss",$id,$firstname,$lastname,$email,$password);
      $create->execute();

}






}



class Products{

  private $conn;

public function __construct()
{
  $this->conn = (new Connection())->conn;
}



public function createProducts($id,$product_name,$product_size,$product_type,$product_category,
$product_price,$product_quantity,$product_image){
        $products =$this->conn->prepare("INSERT INTO products (id,product_name,product_size,product_type,
        product_category,product_price,product_quantity,product_image) VALUES(?,?,?,?,?,?,?,?,?)");
        $products->bind_param("ssssssss",$id,$product_name,$product_size,$product_type,$product_category,
        $product_price,$product_quantity,$product_image);
        $products->execute();

}

public function findbyCategory($product_category){
$category = $this->conn->prepare("SELECT * FROM products WHERE product_category = ?");
$category->bind_param("s",$product_category);
$category->execute();
return $category->get_result()->fetch_assoc();
}


public function findbyProduct($product_name){
$pname = $this->conn->prepare("SELECT * FROM products WHERE product_name=?");
$pname->bind_param("s",$product_name);
$pname->execute();
return $pname->get_result()->fetch_assoc();
}


public function detailsOnly($id,$product_name,$product_size,$product_type,$product_category,
$product_price,$product_quantity){
        $products =$this->conn->prepare("INSERT INTO products (id,product_name,product_size,product_type,
        product_category,product_price,product_quantity) VALUES(?,?,?,?,?,?,?,?)");
        $products->bind_param("sssssdi",$id,$product_name,$product_size,$product_type,$product_category,
        $product_price,$product_quantity,$product_image);
        $products->execute();

}



}








?>