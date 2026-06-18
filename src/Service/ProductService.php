<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    // construct avec injection de dépendances, pour la flexibilité de faire des mock en test
    public function __construct(private ProductRepository $product_repository, private EntityManagerInterface $entityManager)
    {
        
    }

    public function getAllProducts() : array
    {
        return $this->product_repository->findAll();
    }

    public function getOneProduct(int $id): ?Product
    {
        return $this->product_repository->findOneById($id);
    }

    public function isAvailable(Product $product)
    {
        return $product->getStock() > 0;
    }

    public function addProduct(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();

    }

    public function updateProduct(Product $product): void
    {
        $this->entityManager->flush();
    }

    public function deleteProduct(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}


// donnée moqué. __construct dans une class, injection et flexibilité des Mocks