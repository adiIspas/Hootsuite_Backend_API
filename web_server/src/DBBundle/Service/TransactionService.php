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
use Documents\CustomRepository\Repository;

class TransactionService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrineManager;

    /**
     * TransactionService constructor.
     * @param ManagerRegistry $doctrineManager
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrineManager = $doctrine;
    }

    /**
     * Add a transaction in system.
     *
     * @param Transaction $transaction
     * @return mixed
     */
    public function addTransaction(Transaction $transaction)
    {
        $repository = $this->getRepository('Transaction');
        $result = $repository->addTransaction($transaction);

        return $result;
    }

    /**
     * Get repository by name.
     *
     * @param String $repositoryName
     * @return Repository
     */
    private function getRepository($repositoryName)
    {
        $repository = $this->doctrineManager->getRepository('DBBundle:'.$repositoryName);

        return $repository;
    }
}