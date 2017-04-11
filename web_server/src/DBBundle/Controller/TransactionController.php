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
}
