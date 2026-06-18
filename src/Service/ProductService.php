<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    // construct avec injection de dépendances, pour la flexibilité de faire des mock en test
    public function __construct(private ProductRepository $product_repository)
    {
        
    }

    public function getAllProducts() : array
    {
        return $this->product_repository->findAll();
    }

    public function getOneProduct(int $id)
    {
        return $this->product_repository->findOneById($id);
    }
}


// donnée moqué. __construct dans une class, injection et flexibilité des Mocks