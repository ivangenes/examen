<?php
namespace App\Services;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;


class CategoryService {

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    public function __construct( EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * create Category
    */
    public function createCategory($cData){

        $category = new Category();
        $category->setName($cData['name']);
        $category->setDescription($cData['description']);

        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    /**
    * get all categories
    */
    public function getAllCategories(){
        return $this->em->getRepository(Category::class)->findAll();
    }

    /**
     * get category by id
    */
    public function getCategory($id){
        return $this->em->getRepository(Category::class)->findOneById($id);
    }

    /**
    * update categories
    */
    public function updateCategory(int $id, $data){
        
        $category = $this->em->getRepository(Category::class)->findOneById($id);
        
        if(empty($category)){
            return null;
        }
        $category->setName($data['name']);
        $category->setDescription($data['description']);
        $this->em->flush();

        return $category;
    }
}