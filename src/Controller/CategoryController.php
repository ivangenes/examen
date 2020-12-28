<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Services\CategoryService;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, CategoryService $categoryService): Response
    {
        return $this->json($categoryService->getAllCategories());
    }

    /**
     * @Route("/createCategory", name="createCategory", methods={"POST"})
     */
    public function createCategory(RequestStack $request , CategoryService $categoryService): Response
    {
        $data = json_decode($request->getCurrentRequest()->getContent(),true);
        return $this->json($categoryService->createCategory($data));
    }

    /**
     * @Route("/{id}", name="showCategory", methods={"GET"})
     */
    public function show(Category $category, CategoryService $categoryService ): Response
    {
        return $this->json($categoryService->getCategory($category));  
    }

    /**
     * @Route("/update/{id}", name="categoryUpdate", methods={"PUT"})
     */
    public function update(RequestStack $request, int $id, CategoryService $categoryService ): Response
    {
        $data = json_decode($request->getCurrentRequest()->getContent(),true);
        return $this->json($categoryService->updateCategory($id,$data));  
    }

}
