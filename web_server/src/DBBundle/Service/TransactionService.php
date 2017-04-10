<?php
namespace DBBundle\Service;
/**
 * Created by PhpStorm.
 * User: Adrian Ispas
 * Date: 10.04.2017
 * Time: 20:20
 */

use Doctrine\ODM\MongoDB;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use DBBundle\Repository\TransactionRepository;
use DBBundle\Document\Transaction;
use Doctrine\ORM\EntityManager;

class TransactionService
{
    private $doctrineManager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrineManager = $doctrine;
    }

    public function addTransaction(Transaction $transaction)
    {
        $repository = $this->getRepository('Transaction');
        $result = $repository->addTransaction($transaction);

        return $result;
    }

    private function getRepository($repositoryName)
    {
        $repository = $this->doctrineManager->getRepository('DBBundle:'.$repositoryName);

        return $repository;
    }
}