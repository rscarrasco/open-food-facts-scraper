<?php

class Product {
    private static $connection;
    private $table = "products";

    public $code;
    public $barcode;
    public $status;
    public $imported_t;
    public $url;
    public $product_name;
    public $quantity;
    public $categories;
    public $packaging;
    public $brands;
    public $image_url;

    public $isUnset = true;

    public static function setConnection($connection){
        Product::$connection = $connection;
    }

    public function setData($data){
        $this->code         = $data['code'];
        $this->barcode      = $data['barcode'];
        $this->status       = $data['status'];
        $this->imported_t   = $data['imported_t'];
        $this->url          = $data['url'];
        $this->product_name = $data['product_name'];
        $this->quantity     = $data['quantity'];
        $this->categories   = $data['categories'];
        $this->packaging    = $data['packaging'];
        $this->brands       = $data['brands'];
        $this->image_url    = $data['image_url'];

        $this->isUnset = false;
    }

    public function getData(){
        $data = array();

        $data['code']         = $this->code;
        $data['barcode']      = $this->barcode;
        $data['status']       = $this->status;
        $data['imported_t']   = $this->imported_t;
        $data['url']          = $this->url;
        $data['product_name'] = $this->product_name;
        $data['quantity']     = $this->quantity;
        $data['categories']   = $this->categories;
        $data['packaging']    = $this->packaging;
        $data['brands']       = $this->brands;
        $data['image_url']    = $this->image_url;
        
        return $data;
    }

    private function buildStoreQuery(){
        return <<<SQL
INSERT INTO $this->table (
       code, 
       barcode, 
       status, 
       imported_t, 
       url, 
       product_name, 
       quantity, 
       categories, 
       packaging, 
       brands, 
       image_url
)
VALUES (
       "$this->code", 
       "$this->barcode", 
       "$this->status", 
       "$this->imported_t", 
       "$this->url", 
       "$this->product_name", 
       "$this->quantity", 
       "$this->categories", 
       "$this->packaging", 
       "$this->brands", 
       "$this->image_url"
)   ON DUPLICATE KEY UPDATE
       barcode = "$this->barcode",
       status = "$this->status",
       imported_t = "$this->imported_t",
       url = "$this->url",
       product_name = "$this->product_name",
       quantity = "$this->quantity",
       categories = "$this->categories",
       packaging = "$this->packaging",
       brands = "$this->brands",
       image_url = "$this->image_url";
SQL;
    }

    public function store(){
        if($this->isUnset){
            throw new Exception("Cannot store Product: uninitialized Product.");
        }

        if(!Product::$connection){
            throw new Exception("Cannot store Product: missing database connection.");
        }
       $sql = $this->buildStoreQuery();
       Product::$connection->exec($sql);
    }

    public static function fetchSingle($code){
        $sql = "SELECT * FROM products WHERE code = '$code'";
        $stmt= Product::$connection->query($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row){
            $result = new Product();
            $result->setData($row);
            return $result;
       } 
       return false;
    }

    public static function fetchAll($start, $limit){
        $sql = "SELECT * FROM products LIMIT $start,$limit";
        $stmt= Product::$connection->query($sql);
        $stmt->execute();
        $result = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
           $product = new Product();
           $product->setData($row);
           $result[] = $product;
        }
        return $result;
    }
}

