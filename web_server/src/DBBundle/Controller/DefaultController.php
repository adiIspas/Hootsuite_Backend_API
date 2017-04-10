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

class DefaultController extends Controller
{
    /**
     * @FOS\View()
     * @FOS\Post("/transactions")
     */
//    public function postAddTransactionAction(Request $request)
    public function postAddTransactionAction()
    {
//        $requestParams = $request->request->all();
//        $sender = $requestParams['sender'];
//        $receiver = $requestParams['receiver'];
//        $timestamp = $requestParams['timestamp'];
//        $sum = $requestParams['sum'];

//        $product = new Transaction();
//        $product->setReceiverId($rec);
//        $product->setSenderId(2);
//
//        $dm = $this->get('doctrine_mongodb')->getManager();
//        $dm->persist($product);
//        $dm->flush();
//
//        return new Response('Created product id '. $product->getId());

        $transaction = $this->get('doctrine_mongodb')
            ->getManager()
            ->getRepository('DBBundle:Transaction')
            ->findAllOrderedById();

        return new Response('Merge');
    }
}
