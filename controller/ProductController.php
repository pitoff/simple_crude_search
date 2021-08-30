<?php

namespace app\controller;

use app\model\Product;
use app\Router;

class ProductController{

    public function index(Router $router)
    {
        $keyword = $_GET['search'] ?? '';
        $products = $router->db->getProducts($keyword);
        $router->renderView('products/index', [
            'products' => $products,
            'keyword' => $keyword
        ]);
    }

    public function create(Router $router)
    {
        $errors = [];

        $productData = [
            'title' => '',
            'description' => '',
            'image' => '',
            'price' => ''
        ];
        if($_SERVER["REQUEST_METHOD"] === 'POST'){

            $productData['title'] = $_POST['title'];
            $productData['description'] = $_POST['description'];
            $productData['imageFile'] = $_FILES['image'] ?? null;
            $productData['price'] = (float)$_POST['price'];
           
            $product = new Product();
            $product->load($productData);
            $errors = $product->save();

            if(empty($errors)){
                header('location: /products');
            }
            
        }
        $router->renderView('products/create', [
            'product' => $productData,
            'errors' => $errors
        ]);
    }

    public function update(Router $router)
    {
        $id  = $_GET['id'] ?? 'null';
        if(!$id){
            header('location: /products');
            exit;
        }

        $productData = $router->db->getProductsById($id);

        $errors = [];

        if($_SERVER["REQUEST_METHOD"] === 'POST'){

            $productData['title'] = $_POST['title'];
            $productData['description'] = $_POST['description'];
            $productData['imageFile'] = $_FILES['image'] ?? null;
            $productData['price'] = (float)$_POST['price'];

            $product = new Product();
            $product->load($productData);
            $errors = $product->save();

            if(empty($errors)){
                header('location: /products');
                exit;
            }
        
        }
        
        $router->renderView('products/update', [
            'product' => $productData,
            'errors' => $errors
        ]);
    }

    public function delete(Router $router)
    {
        $id = $_POST['id'] ?? null;
        if(!$id){
            header('location: /products');
            exit;
        }

        $router->db->deleteProduct($id);
        header('location: /products');
        
    }
}