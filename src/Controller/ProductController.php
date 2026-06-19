<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{

    // private ProductService $ps;

    #[Route('/', name: 'app_products')]
    public function index(ProductService $ps): Response
    {
        // autowiring
        $products = $ps->getAllProducts();
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'app_product_new', methods: ['POST' , 'GET'])]
    public function new(Request $request, ProductService $ps): Response
    {
        $newProduct = new Product();
        $FormNewProduct = $this->createForm(ProductType::class, $newProduct);
        $FormNewProduct->handleRequest($request);

        if ($FormNewProduct->isSubmitted() && $FormNewProduct->isValid()) {
            $ps->addProduct($newProduct);

            return $this->redirectToRoute('app_products');
        }

        return $this->render('product/new.html.twig', [
            'FormNewProduct' => $FormNewProduct,
        ]);
    }

    #[Route('/{id}', name: 'app_product', methods: ['GET'])]
    public function show(int $id, ProductService $ps): Response
    {

        $product = $ps->getOneProduct($id);
        $isAvailable = $ps->isAvailable($product);

        if (!$product) {
            throw $this->createNotFoundException('produit introuvable');
        }

        return $this->render('product/show.html.twig',[
            'product' => $product,
            'isAvailable' => $isAvailable,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['POST', 'GET'])]
    public function edit(int $id, Request $request, ProductService $ps): Response
    {
        $product = $ps->getOneProduct($id);

        if (!$product) {
            throw $this->createNotFoundException('produit introuvable');
        }

        $FormEditProduct = $this->createForm(ProductType::class, $product);
        $FormEditProduct->handleRequest($request);

        if ($FormEditProduct->isSubmitted() && $FormEditProduct->isValid()) {
            $ps->updateProduct($product);

            return $this->redirectToRoute('app_product', array(
                'id' => $product->getId(),
            ));
        }

        return $this->render('product/edit.html.twig', [
            'FormEditProduct' => $FormEditProduct,
            'product' => $product,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_product_delete', methods: ['POST'])]
    public function delete( int $id, ProductService $ps ): Response
    {
        $product = $ps->getOneProduct($id);

        if (!$product) {
            throw $this->createNotFoundException('produit introuvable');
        }

        $ps->deleteProduct($product);

        return $this->redirectToRoute('app_products');
    }
}

// faire une factorisation est pratique pour :
// 1 test -> isolé et tester une fois
// 2 lecture -> plus facile à lire les de, plus clair
// 3 reutilisation -> faire le code une fois et le rappeler partout
// 4 MAINTENABILITE