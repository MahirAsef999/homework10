<?php

namespace app\controllers;

use app\models\Product;

class ProductController
{
    public function validateProduct($inputData) {
        $errors = [];

        $name = $inputData['name'];
        $category = $inputData['category'];
        $price = $inputData['price'];

        if ($name) {
            $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
            if (strlen($name) < 2) {
                $errors['nameShort'] = 'Product name is too short';
            }
        } else {
            $errors['requiredName'] = 'Product name is required';
        }

        if ($category) {
            $category = htmlspecialchars($category, ENT_QUOTES | ENT_HTML5, 'UTF-8', true);
        } else {
            $errors['requiredCategory'] = 'Category is required';
        }

        if ($price !== null && $price !== '') {
            if (!is_numeric($price) || $price < 0) {
                $errors['invalidPrice'] = 'Price must be a positive number';
            }
        } else {
            $errors['requiredPrice'] = 'Price is required';
        }

        if (count($errors)) {
            http_response_code(400);
            echo json_encode($errors);
            exit();
        }

        return [
            'name' => $name,
            'category' => $category,
            'price' => $price,
        ];
    }

    // get all products
    public function getAllProducts() {
        $productModel = new Product();
        header("Content-Type: application/json");
        $products = $productModel->getAllProducts();
        echo json_encode($products);
        exit();
    }

    // get one product
    public function getProductByID($id) {
        $productModel = new Product();
        header("Content-Type: application/json");
        $product = $productModel->getProductById($id);
        echo json_encode($product);
        exit();
    }

    // search products 
    public function searchProducts() {
        $keyword = $_GET['keyword'] ?? '';
        $productModel = new Product();
        header("Content-Type: application/json");
        $results = $productModel->searchProducts($keyword);
        echo json_encode($results);
        exit();
    }

    // create product
    public function saveProduct() {
        $inputData = [
            'name' => $_POST['name'] ?? null,
            'category' => $_POST['category'] ?? null,
            'price' => $_POST['price'] ?? null,
        ];

        $productData = $this->validateProduct($inputData);

        $product = new Product();
        $product->saveProduct($productData);

        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    // update product
    public function updateProduct($id) {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        parse_str(file_get_contents('php://input'), $_PUT);

        $inputData = [
            'name' => $_PUT['name'] ?? null,
            'category' => $_PUT['category'] ?? null,
            'price' => $_PUT['price'] ?? null,
        ];

        $productData = $this->validateProduct($inputData);
        $productData['id'] = $id;

        $product = new Product();
        $product->updateProduct($productData);

        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    // delete product
    public function deleteProduct($id) {
        if (!$id) {
            http_response_code(404);
            exit();
        }

        $product = new Product();
        $product->deleteProduct($id);

        http_response_code(200);
        echo json_encode(['success' => true]);
        exit();
    }

    // views
    public function productsView() {
        include '../public/assets/views/product/products-view.html';
        exit();
    }

    public function productsAddView() {
        include '../public/assets/views/product/products-add.html';
        exit();
    }

    public function productsDeleteView() {
        include '../public/assets/views/product/products-delete.html';
        exit();
    }

    public function productsUpdateView() {
        include '../public/assets/views/product/products-update.html';
        exit();
    }
}
