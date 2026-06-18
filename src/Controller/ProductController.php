<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products')]
    public function index(ProductService $productService): Response
    {
        // autowiring
        $products = $productService->getAllProducts();
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{id}', name: 'app_product', methods: ['GET'])]
    public function show(int $id, ProductService $productService): Response
    {

        $product = $productService->getOneProduct($id);

        return $this->render('product/show.html.twig',[
            'product' => $product,
        ]);
    }
}
