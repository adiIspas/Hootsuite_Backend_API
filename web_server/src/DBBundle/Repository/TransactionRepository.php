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
        $user = intval($user);
        $threshold = intval($threshold);

        // Extract day of transaction query
        $dayOfQuery = gmdate("d-m-Y",$day);

        // Determinate the begin and the end of day in unix timestamp
        $tsBeginDay = strtotime($dayOfQuery);
        $tsEndDay = $tsBeginDay + 86399;

        // Create query
        $qb = $this->createQueryBuilder()
                   ->hydrate(false)
                   ->eagerCursor(true)
                   ->select('sender_id', 'receiver_id', 'ts', 'sum');

        // Select user
        $qb
            ->addOr($qb->expr()->field('sender_id')->equals($user))
            ->addOr($qb->expr()->field('receiver_id')->equals($user));

        // Select day
        $qb
            ->addAnd($qb->expr()->field('ts')->range($tsBeginDay,$tsEndDay));

        // Select threshold
        $qb
            ->addAnd($qb->expr()->field('sum')->gte($threshold));

        // Execute query
        $query = $qb->getQuery();
        $transactions = $query->execute()
                              ->toArray();

//        return new Response(json_encode($transactions),200);
        return json_encode($transactions);
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
        $balance = 0;
        $user = intval($user);

        // Extract since day of transaction query (the begin of day)
        $sinceDay = gmdate("d-m-Y",$since);
        $tsSinceDay = strtotime($sinceDay);

        // Extract until day of transaction query (the end of day)
        $untilDay = gmdate("d-m-Y",$until);
        $tsUntilDay = strtotime($untilDay) + 86399;

        // Create query for sender user
        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('sum');

        // Select sender user
        $qb
            ->field('sender_id')->equals($user);

        // Select day
        $qb
            ->addAnd($qb->expr()->field('ts')->range($tsSinceDay, $tsUntilDay));

        // Execute query
        $query = $qb->getQuery();
        $sums = $query->execute()->toArray();

        foreach ($sums as $currentValue) {
            $balance -= $currentValue["sum"];
        }

        // Create query for receiver user
        $qb = $this->createQueryBuilder()
            ->hydrate(false)
            ->select('sum');

        // Select receiver user
        $qb
            ->field('receiver_id')->equals($user);

        // Select day
        $qb
            ->addAnd($qb->expr()->field('ts')->range($tsSinceDay, $tsUntilDay));

        // Execute query
        $query = $qb->getQuery();
        $sums = $query->execute()->toArray();

        foreach ($sums as $currentValue) {
            $balance += $currentValue["sum"];
        }

//        return new Response($balance,200);
        return json_encode($balance);
    }
}