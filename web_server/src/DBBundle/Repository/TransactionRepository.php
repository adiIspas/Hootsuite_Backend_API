<?php

namespace DBBundle\Repository;

/**
 * Created by PhpStorm.
 * User: Adrian Ispas
 * Date: 10.04.2017
 * Time: 17:24
 */

use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

class TransactionRepository extends DocumentRepository
{

    public function findAllOrderedById()
    {
        return $this->createQueryBuilder()
            ->sort('sender_id', 'ASC')
            ->getQuery()
            ->execute();
    }

}