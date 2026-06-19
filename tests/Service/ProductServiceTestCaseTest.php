<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ProductServiceTestCaseTest extends TestCase
{

    private ProductService $ps;

    protected function setUp(): void
    {
        $productRepository = $this->createMock(ProductRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $this->ps = new ProductService($productRepository, $entityManager);
    }    

    function testIsAvailableReturnsFalseWhenStockIsZero(): void
    {

        $product = (new Product())
            ->setName('Chaise')
            ->setPrice(100)
            ->setStock(0);

        $result = $this->ps->isAvailable($product);

        $this->assertFalse($result);
    }

    // une methode pour tester que isAvailable renvois true quand le stock est positive
    public function testIsAvailableReturnsTrueWhenStockIsPositif() : void
    {
        $product = (new Product())
            ->setName('Chaise')
            ->setPrice(100)
            ->setStock(20);

        $result = $this->ps->isAvailable($product);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
    
}