<?php

namespace DBBundle\Controller;

use DBBundle\Document\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\Annotations\RequestParam;

class TransactionController extends Controller
{
    /**
     * Add a transaction in system.
     *
     * @FOS\View()
     * @FOS\Post("/transactions")
     *
     * @param Request $request
     * @return Response
     */
    public function postAddTransactionAction(Request $request)
    {
        $requestParams = $request->request->all();
        $payloadSize = $this->getParameter('payload_size');
        $payloadKeys = $this->getParameter('payload_keys');

        if(sizeof($requestParams) == $payloadSize) {
            $sender = $requestParams['sender'];
            $receiver = $requestParams['receiver'];
            $timestamp = $requestParams['timestamp'];
            $sum = $requestParams['sum'];

            $transaction = new Transaction();
            $transaction->setSenderId($sender);
            $transaction->setReceiverId($receiver);
            $transaction->setTs($timestamp);
            $transaction->setSum($sum);

            $transactionService = $this->container->get('db.transaction_service');

            return new Response($transactionService->addTransaction($transaction));
        }
        else {

            $missedParams = null;
            foreach ($payloadKeys as $currentParam){
                if(!array_key_exists($currentParam, $requestParams)) {
                    $missedParams[] = $currentParam;
                }
            }

            return new Response('Incomplete payload! Parameters ' . implode(", ",$missedParams) . ' are missing.', 400);
        }
    }

    /**
     * List all transactions in which the user was involved in the given day.
     *
     * @FOS\View()
     * @FOS\Get("/transactions/")
     *
     * Request $request
     * @return mixed
     */
    public function getTransactionsAction(Request $request)
    {
        $user = $request->get('user');
        $day = $request->get('day');
        $threshold = $request->get('threshold');

        $transactionService = $this->container->get('db.transaction_service');

        return new Response($transactionService->getTransactions($user, $day, $threshold));
    }

    /**
     * Compute user balance in the given time frame.
     *
     * @FOS\View()
     * @FOS\Get("/balance/")
     *
     * Request $request
     * @return mixed
     */
    public function getBalanceAction(Request $request)
    {
        $user = $request->get('user');
        $since = $request->get('since');
        $until = $request->get('until');

        $transactionService = $this->container->get('db.transaction_service');

        return new Response($transactionService->getBalance($user, $since, $until));
    }
}