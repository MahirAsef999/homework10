<?php
namespace app\models;

use app\models\Model;

class Product extends Model {

    // get all products
    public function getAllProducts() {
        $query = "SELECT * FROM products ORDER BY name";
        return $this->query($query);
    }

    // search products by name
    public function searchProducts($keyword) {
        $query = "SELECT * FROM products WHERE name LIKE :keyword ORDER BY name";
        return $this->query($query, [
            'keyword' => '%' . $keyword . '%'
        ]);
    }
    
    // get product by id
    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        return $this->query($query, ['id' => $id]);
    }

    // create product
    public function saveProduct($productData) {
        $query = "INSERT INTO products (name, category, price) VALUES (:name, :category, :price)";
        return $this->query($query, $productData);
    }

    // update product
    public function updateProduct($productData) {
        $query = "UPDATE products SET name = :name, category = :category, price = :price WHERE id = :id";
        return $this->query($query, $productData);
    }

    // delete product
    public function deleteProduct($id) {
        $query = "DELETE FROM products WHERE id = :id";
        return $this->query($query, ['id' => $id]);
    }
}
