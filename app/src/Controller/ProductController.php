<?php

namespace App\Controller;


use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



class ProductController extends AbstractController
{

    public function getProducts(Request $request, ManagerRegistry $doctrine, SerializerInterface $serializer){

        /*Request parameters*/
        $category = $request->query->get('category') ?? '';
        $priceLessThan = $request->query->get('priceLessThan') ?? '';

        /*Input validation*/
        if(!empty($category) && gettype($category) != 'string'){
            return new JsonResponse('Invalid category type');
        }
        if(!empty($priceLessThan) && is_int($priceLessThan)){
            return new JsonResponse('Invalid priceLessThan value');
        }


        /*Base query*/
        $query = $doctrine->getManager()->createQueryBuilder()
            ->select('p.sku, p.category ,p.name , p.price')
            ->from('App:Products', 'p')
            ->setMaxResults( '5' )
        ;

        /*Add conditional clauses to query*/
        if(!empty($category)){
            $query->Where('p.category = :category');
            $query->setParameter('category', $category);
        }
        if(!empty($priceLessThan)){
            $query->andWhere('p.price <= '.$priceLessThan);
        }

        /*Query Results*/
        $results= $query->getQuery()->getResult();

        /*Process Results (apply discounts)*/
        $results = $this->processResult($results);

        return new JsonResponse($results);

    }


    public function addProducts(Request $request, ManagerRegistry $doctrine){
        set_time_limit(0);
        $i =0;
        $batch = 200;

        $requestArr = $request->get('data');
        if(empty($requestArr)){
            return new Response('Parameter cannot be empty');
        }
        $requestArr = json_decode($requestArr,1);
        foreach ($requestArr['products'] as $value){
            $i++;
            $entityManager = $doctrine->getManager();

            $product = new Products();
            $product->setSku($value['sku']);
            $product->setName($value['name']);
            $product->setCategory($value['category']);
            $product->setPrice($value['price']);

            $entityManager->persist($product);

            if($i == $batch){
                $entityManager->flush($product);
                $entityManager->detach($product);
                $entityManager->clear($product);
                $i =0;
            }

        }
        $entityManager->flush();

        return new Response('New products added');
    }

    private function processResult($results){
        foreach ($results as $key => $value){

            $originalPrice = $value['price'];

            $results[$key]['price'] = array();
            $results[$key]['price']['original'] = $originalPrice;

            if($value['category']== 'boots'){

                $results[$key]['price']['final'] =number_format((float)$originalPrice * 0.7,0,'.','');
                $results[$key]['price']['discount_percentage'] = '30%';
            }
            else if($value['sku']=='000003'){

                $results[$key]['price']['final'] = number_format((float)$originalPrice * 0.85,0,'.','');;
                $results[$key]['price']['discount_percentage'] = '15%';
            }
            else{

                $results[$key]['price']['final'] = $originalPrice;
                $results[$key]['price']['discount_percentage'] = null;
            }

            $results[$key]['price']['currency'] = 'EUR';      // common for all
        }
        return $results;
    }


    /*
     * $encoders = [new XmlEncoder(), new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $serializer = new Serializer($normalizers, $encoders);
    $res = $serializer->serialize($products, 'json');
    $res = $request->attributes->all();
    $products = $doctrine->getRepository(Products::class)->findAll();*/
}