<?php
namespace DBBundle\Service;
/**
 * Created by PhpStorm.
 * User: Adrian Ispas
 * Date: 10.04.2017
 * Time: 20:20
 */

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use DBBundle\Document\Transaction;
use Documents\CustomRepository\Repository;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function addTransaction(Transaction $transaction)
    {
        $repository = $this->getRepository('Transaction');
        $response = $repository->addTransaction($transaction);

        return $response;
    }

    /**
     * List all transactions in which the user was involved in the given day.
     *
     * @param integer $user
     * @param integer $day
     * @param integer $threshold
     * @return Response
     */
    public function getTransactions($user, $day, $threshold)
    {
        $repository = $this->getRepository('Transaction');
        $response = $repository->getTransactions($user, $day, $threshold);

        return $response;
    }

    /**
     * Compute user balance in the given time frame.
     *
     * @param integer $user
     * @param integer $since
     * @param integer $until
     * @return Response
     */
    public function getBalance($user, $since, $until)
    {
        $repository = $this->getRepository('Transaction');
        $response = $repository->getBalance($user, $since, $until);

        return $response;
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