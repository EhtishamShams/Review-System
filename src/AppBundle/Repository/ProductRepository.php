<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 16/08/2018
 * Time: 6:44 AM
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Shop;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findAllProductsByShop(Shop $shop){
        return $this->createQueryBuilder('product')
            ->andWhere('product.shop = :shop')
            ->setParameter('shop', $shop)
            ->orderBy('product.name')
            ->getQuery()
            ->execute();
    }
}