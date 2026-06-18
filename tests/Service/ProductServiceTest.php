<?php

namespace App\Tests\Service;

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
}