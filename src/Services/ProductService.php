<?php
namespace App\Services;

use App\Entity\Product;
use App\Entity\Category;
use App\Enum\ProductEnum;
use App\Model\ProductModel;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;


class ProductService {

    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    public function __construct( EntityManagerInterface $em){

        $this->em = $em;
    }

    /**
    * get all products
    */
    public function getAllProducts(){

        return $this->em->getRepository(Product::class)->findAll();
    }

    /**
    * create products
    */
    public function createProduct($data){

        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setCurrency($data['currency']);
        $product->setFeatured($data['featured']);
        $category=null;
        if(!empty($data['category'])){
            $category = $this->em->getRepository(Category::class)->findOneById($data['category']);
        }
        $product->setCategory($category);
        $this->em->persist($product);
        $this->em->flush();
        return $product;
    }

    public function productFeatured($filters){

        $products =  $this->em->getRepository(Product::class)->findByFeatured(ProductEnum::featured);
        $result = [];
        foreach ($products as $product){
            $model = new ProductModel();
            $model->setId($product->getId());
            $model->setName($product->getName());
            if($filters['currency']){
                $model->setPrice($this->calculePrice($filters['currency'],$product ));
                $model->setCurrency($filters['currency']);
            }else{
                $model->setPrice($product->getPrice());
                $model->setCurrency($product->getCurrency());
            }          
            $model->setNameCategory($product->getCategory() ? $product->getCategory()->getName(): "");
            $result[] = $model;
        }
        return $result;
       
    }

    public function calculePrice($currency,$product){

        $data = json_decode($this->executeCurl($currency),true);
        if($currency === 'USD'){
            if($product->getCurrency() == ProductEnum::USD){
                    return $product->getPrice();
            }else{
                return $product->getPrice() / $data['rates']['EUR'];
            }

        }
        if($currency == 'EUR'){
            if($product->getCurrency() == ProductEnum::EUR){
                    return $product->getPrice();
            }else{
                return $product->getPrice() / $data['rates']['USD'];
            }

        }
        return $product->getPrice();
    }

    public function executeCurl($currency){

        if($currency === ProductEnum::USD ){
            $url='https://api.exchangeratesapi.io/latest?base=USD&symbols=EUR';
        }else{
            $url='https://api.exchangeratesapi.io/latest?base=EUR&symbols=USD';
        }

        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($cURL, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
        curl_setopt($cURL, CURLOPT_FOLLOWLOCATION, true);  
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true); 
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($cURL, CURLOPT_TIMEOUT, 20);
       
        $result = curl_exec($cURL);
        curl_close($cURL);
        return $result;
    }
}