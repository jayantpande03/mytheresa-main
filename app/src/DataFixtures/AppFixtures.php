<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Products;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
//        $entityManager = $doctrine->getManager();
//        $product1 = new Products();
//        $product1->setSku('000001');
//        $product1->setName('BV Lean leather ankle boots');
//        $product1->setCategory('boots');
//        $product1->setPrice(89000);
//        $manager->persist();
//
//        $product2 = new Products();
//        $product2->setSku('000002');
//        $product2->setName('BV Lean leather ankle boots');
//        $product2->setCategory('boots');
//        $product2->setPrice(99000);
//        $manager->persist();
//
//        $product3 = new Products();
//        $product3->setSku('000003');
//        $product3->setName('Ashlington leather ankle boots');
//        $product3->setCategory('boots');
//        $product3->setPrice(71000);
//        $manager->persist();
//
//        $product3 = new Products();
//        $product3->setSku('000004');
//        $product3->setName('Naima embellished suede sandals');
//        $product3->setCategory('sandals');
//        $product3->setPrice(79500);
//        $manager->persist();
//
//        $entityManager->flush();
    }
}
