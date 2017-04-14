<?php

namespace DBBundle\Repository;

/**
 * Created by PhpStorm.
 * User: Adrian Ispas
 * Date: 10.04.2017
 * Time: 17:24
 */

use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\HttpFoundation\Response;
use DBBundle\Document\Transaction;

class TransactionRepository extends DocumentRepository
{
    /**
     * Add a transaction in system.
     *
     * @param Transaction $transaction
     * @return Response
     */
    public function addTransaction(Transaction $transaction)
    {
        $this->createQueryBuilder()
            ->insert()
            ->field('sender_id')->set(intval($transaction->getSenderId()))
            ->field('receiver_id')->set(intval($transaction->getReceiverId()))
            ->field('ts')->set(intval($transaction->getTs()))
            ->field('sum')->set(intval($transaction->getSum()))
            ->getQuery()
            ->execute();

        return new Response('The transaction was successfully added!',200);
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
        $dayIntervalTS = range($day,$day+100);

//        $transactions = $this->createQueryBuilder()
//                             ->field('receiver_id')->equals($user)
//                             ->field('ts')->in($dayIntervalTS)
//                             ->field('sum')->gte($threshold)
//                             ->getQuery()
//                             ->execute();

//        $transactions = $this->createQueryBuilder()
//            ->select('sender_id')
//            ->field('sender_id')->equals($user)
//            ->getQuery()
//            ->execute();

        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('sender_id', 'receiver_id', 'sum');
        $query = $qb->getQuery();
        $users = $query->execute()->toArray();

//        return new Response($user . ' ' . $day . '  ' . $threshold,200);
        return new Response(json_encode($users),200);
//        return new Response(json_encode($dayIntervalTS),200);
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
        return new Response($user . ' ' . $since . '  ' . $until,200);
    }
}