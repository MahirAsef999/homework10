<?php

require_once "../app/models/Model.php";
require_once "../app/models/Product.php";
require_once "../app/controllers/ProductController.php";

// load env variables from .env file
$env = parse_ini_file('../.env');

define('DBNAME', $env['DBNAME']);
define('DBHOST', $env['DBHOST']);
define('DBUSER', $env['DBUSER']);
define('DBPASS', $env['DBPASS']);

use app\controllers\ProductController;

// get uri without query strings
$uri = strtok($_SERVER["REQUEST_URI"], '?');

// get uri pieces
$uriArray = explode("/", $uri);

if ($uriArray[1] === 'api' && $uriArray[2] === 'products') {
    $productController = new ProductController();

    // get one or all products 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
        if (isset($_GET['keyword'])) {
            $productController->searchProducts();
        } elseif ($id) {
            $productController->getProductByID($id);
        } else {
            $productController->getAllProducts();
        }
    }

    // create or delete (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
            $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
            $productController->deleteProduct($id);  
        } else {
            $productController->saveProduct();
        }
    }

    // update product
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
        $productController->updateProduct($id);
    }

    // delete product
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = isset($uriArray[3]) ? intval($uriArray[3]) : null;
        $productController->deleteProduct($id);  
    }

    exit();
}

// views 

if ($uri === '/' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsView();
}

if ($uri === '/new-product' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsAddView();
}

if ($uriArray[1] === 'update-product' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsUpdateView();
}

if ($uriArray[1] === 'delete-product' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $productController = new ProductController();
    $productController->productsDeleteView();
}

// if no match, show not found page
include '../public/assets/views/notFound.html';
exit();
