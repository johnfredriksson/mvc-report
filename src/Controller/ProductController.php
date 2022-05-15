<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/product/create", name="create_product")
     */
    public function createProduct(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName("Keyboard_num_" . rand(1, 9));
        $product->setValue(rand(100, 999));

        $entityManager->persist($product);

        $entityManager->flush();

        return new Response("Saved new product with id " . $product->getId());
    }

    /**
     * @Route("product/show", name="product_show_all")
     */
    public function showProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository
            ->findAll();

        return $this->json($products);
    }

    /**
     * @Route("/product/show/{productid}", name="product_by_id")
     */
    public function showProductById(
        ProductRepository $productRepository,
        int $productid
    ): Response {
        $product = $productRepository
            ->find($productid);

        return $this->json($product);
    }

    /**
     * @Route("/product/delete/{productid}", name="product_delete_by_id")
     */
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $productid
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($productid);

        if (!$product) {
            throw $this->createNotFoundException(
                "No product found for id " . $productid
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute("product_show_all");
    }

    /**
     * @Route("/product/update/{productid}/{value}", name="product_update")
     */
    public function updateProduct(
        ManagerRegistry $doctrine,
        int $productid,
        int $value
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($productid);

        if (!$product) {
            throw $this->createNotFoundException(
                "No product found for id " . $productid
            );
        }

        $product->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute("product_show_all");
    }
}
