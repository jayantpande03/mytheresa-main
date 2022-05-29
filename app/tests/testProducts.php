<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class testProducts extends WebTestCase
{
    public function  test_add_prodcts_verify_successful_addition(){
        $data = '{"products": [{ "sku": "000001","name": "BV Lean leather ankle boots","category": "boots","price": 89000},{"sku": "000002","name": "BV Lean leather ankle boots","category": "boots","price": 99000},{"sku": "000003","name": "Ashlington leather ankle boots","category": "boots","price": 71000},{"sku": "000004", "name": "Naima embellished suede sandals", "category": "sandals", "price": 79500 }, { "sku": "000005", "name": "Nathane leather sneakers", "category": "sneakers", "price": 59000 } ] }';
        $params = array();
        $params['data']  = $data;
        $client = static::createClient();
        $client->request('POST', '/addproduct',$params);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('New products added', $client->getResponse()->getContent());
    }

    public function test_get_products_verify_successful_response(){
        $client = static::createClient();
        $client->request('GET', '/products');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame('application/json', $client->getResponse()->headers->get('Content-Type'));

    }
    public function test_get_products_filter_category(){
        $params = array();
        $params['category'] = 'boots';
        $client = static::createClient();
        $client->request('GET', '/products', $params);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertLessThanOrEqual(5,sizeof($data));
        foreach ($data as $key => $value){
            $this->assertSame($params['category'],$value['category']);
        }
    }

    public function test_get_products_validate_filter_priceLessThan(){
        $params = array();
        $params['priceLessThan'] = 90000;
        $client = static::createClient();
        $client->request('GET', '/products', $params);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertLessThanOrEqual(5,sizeof($data));
        foreach ($data as $key => $value){
            $this->assertLessThanOrEqual($params['priceLessThan'],$value['price']['original']);
        }
    }

    public function test_get_products_validate_discount(){
        $client = static::createClient();
        $client->request('GET', '/products');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertLessThanOrEqual(5,sizeof($data));
        foreach ($data as $key => $value){
            if($value['category']=='boots'){
                $this->assertEquals(number_format((float)$value['price']['original'] * 0.7,0,'.',''), $value['price']['final']);
            }
            else if($value['sku']=='000003'){
                $this->assertEquals(number_format((float)$value['price']['original'] * 0.85,0,'.',''), $value['price']['final']);
            }
        }
    }
    public function test_get_products_validate_maxResults(){
        $client = static::createClient();
        $client->request('GET', '/products');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertLessThanOrEqual(5,sizeof($data));
    }
}