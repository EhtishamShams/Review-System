<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 20/08/2018
 * Time: 8:36 AM
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Shop;
use Doctrine\ORM\EntityRepository;

class ReviewRepository extends EntityRepository
{
    public function findTopReviews(Shop $shop){
        return $this->createQueryBuilder('review')
            ->andWhere('review.rating > 3')
            ->orderBy('review.rating', 'DESC')
            ->join('review.product', 'prod')
            ->andWhere('prod.shop = :shop')
            ->setParameter('shop', $shop)
            ->getQuery()
            ->execute();
    }
}