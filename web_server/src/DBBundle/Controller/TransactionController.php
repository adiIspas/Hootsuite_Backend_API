<?php

namespace DBBundle\Controller;

use DBBundle\Document\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        if (($response = $this->checkParameters($requestParams, "postAddTransactionAction")) !== true)
            return $response;

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
        $requestParams = $request->query->all();

        if (($response = $this->checkParameters($requestParams, "getTransactionsAction")) !== true)
            return $response;

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
     * @return Response
     */
    public function getBalanceAction(Request $request)
    {
        $requestParams = $request->query->all();

        if (($response = $this->checkParameters($requestParams, "getBalanceAction")) !== true)
            return $response;

        $user = $request->get('user');
        $since = $request->get('since');
        $until = $request->get('until');

        $transactionService = $this->container->get('db.transaction_service');

        return new Response($transactionService->getBalance($user, $since, $until));
    }

    /**
     * Check if a call is correct.
     *
     * @param $requestParams
     * @param $callMethod
     * @return bool|Response
     */
    private function checkParameters($requestParams, $callMethod)
    {
        switch ($callMethod) {
            case 'postAddTransactionAction': {
                $required = ["sender", "receiver", "timestamp", "sum"];

                return ($response = $this->countMissedAndCheckTypeParameters($required, $requestParams)) === true ? true : $response;
            }

            case 'getTransactionsAction': {
                $required = ["user", "day", "threshold"];

                return ($response = $this->countMissedAndCheckTypeParameters($required, $requestParams)) === true ? true : $response;
            }

            case 'getBalanceAction': {
                $required = ["user", "since", "until"];

                return ($response = $this->countMissedAndCheckTypeParameters($required, $requestParams)) === true ? true : $response;
            }
        }

        return false;
    }

    /**
     * Count missed parameters from request.
     *
     * @param $required
     * @param $requestParams
     * @return bool|Response
     */
    private function countMissedAndCheckTypeParameters($required, $requestParams)
    {
        $missedParams = null;
        $finalResponse = '';
        foreach ($required as $currentParam){
            if(!array_key_exists($currentParam, $requestParams)) {
                $missedParams[] = $currentParam;
            }
        }

        if (sizeof($missedParams) > 0){
            $finalResponse = 'Parameters ' . implode(", ",$missedParams) . ' are missing.' . PHP_EOL;
        }

        $missedParams = null;
        $paramsValues = array_values($requestParams);
        foreach ($paramsValues as $currentParamValue){
            if (!filter_var($currentParamValue, FILTER_VALIDATE_INT)) {
                $missedParams[] = array_search($currentParamValue,$requestParams);
            }
        }

        if (sizeof($missedParams) > 0){
            $finalResponse .= 'Parameters ' . implode(", ",$missedParams) . ' are not integers.';
        }

        if ($finalResponse !== '')
            return new Response($finalResponse,400);

        return true;
    }
}