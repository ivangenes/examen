<?php

namespace App\Controller;

use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product")
     */
    public function index(ProductService $productService): Response
    {
        return $this->json($productService->getAllProducts());
    }

    /**
     * @Route("/createProduct", name="createProduct", methods={"POST"})
     */
    public function createCategory(RequestStack $request , ProductService $productService): Response
    {
        $data = json_decode($request->getCurrentRequest()->getContent(),true);
        return $this->json($productService->createProduct($data));
    }

    /**
     * @Route("/featured", name="featuredProduct")
     */
    public function featuredProduct(Request $request,ProductService $productService): Response
    {   
        $filters = [
            'currency' => $request->query->get('currency', null),
        ];
        return $this->json($productService->productFeatured($filters));
    }
}
