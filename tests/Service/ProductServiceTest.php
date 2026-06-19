<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductServiceTest extends KernelTestCase
{
    private ProductService $productService;
    private EntityManagerInterface $em;

    // on prepare les param que nos test auront besoin
    protected function setUp(): void
    {
        // démarre le kernel en mode test
        self::bootKernel();

        // on va recuperer nos service (et leurs injections de dependance) dans le container
        $container = static::getContainer();

        $this->productService = $container->get(ProductService::class);
        $this->em = $container->get(EntityManagerInterface::class);


    }
    
    public function testIsAvailableReturnsFalseWhenStockIsZero() : void
    {
        // ARRANGE : créer un vrais produit en db test
        $product = new Product();
        $product->setName('Table');
        $product->setPrice(10);
        $product->setStock(0);

        $this->em->persist($product);
        $this->em->flush();
        
        // ACT : appeler la méthode que l'on veut tester
        $result = $this->productService->isAvailable($product);

        // ASSERT : vérifier le résultat attendu
        $this->assertFalse($result);
    }

    // une methode pour tester que isAvailable renvois true quand le stock est positive
    public function testIsAvailableReturnsTrueWhenStockIsPositif() : void
    {
        $product = (new Product())
            ->setName('Chaise')
            ->setPrice(100)
            ->setStock(20);
        
        $this->em->persist($product);
        $this->em->flush();

        $result = $this->productService->isAvailable($product);

        $this->assertTrue($result);
    }

    // nettoyer
    protected function tearDown(): void
    {
        // ferme tous le démarrage de kernel - ferme le mode test
        parent::tearDown();

        // 
        // $this->em->createQuery('DELETE FROM App\Entity\Product p')->execute();

        $this->em->close();
    }

}