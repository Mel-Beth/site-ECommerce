<?php
namespace Admin\Controllers;

use Admin\Models\ProductModel;

class ProductsController
{
    public function index()
    {
        $productModel = new ProductModel();
        $products = $productModel->getAllProducts();
        require __DIR__ . '/../Views/products.php';
    }

    public function edit($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);
        require __DIR__ . '/../Views/edit_product.php';
    }
}