<?php

namespace DBBundle\Tests\Controller;

use DBBundle\Document\Transaction;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    //
    // Tests for method postAddTransactionAction
    //
    public function testPostAddTransactionAction_AllValidParameters_True()
    {
        $client = static::createClient();

        // Send all parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                  "receiver" => 2,
                  "timestamp" => 1491990709,
                   "sum" => 15001)
        );

        $this->assertContains("The transaction was successfully added!", $crawler->text());

        $crawler = $client->request('GET', '/transactions/?user=1&day=1491990709&threshold=15001');

        $result = json_decode($crawler->text(),true);

        $transaction = new Transaction();
        $transaction->setSenderId(1);
        $transaction->setReceiverId(2);
        $transaction->setSum(15001);
        $transaction->setTs(1491990709);

        $transactionResult = new Transaction();
        $transactionResult->setSenderId($result[0]["sender_id"]);
        $transactionResult->setReceiverId($result[0]["receiver_id"]);
        $transactionResult->setSum($result[0]["sum"]);
        $transactionResult->setTs($result[0]["ts"]);

        $this->assertEquals($transaction->getSenderId(), $transactionResult->getSenderId());
        $this->assertEquals($transaction->getReceiverId(), $transactionResult->getReceiverId());
        $this->assertEquals($transaction->getSum(), $transactionResult->getSum());
        $this->assertEquals($transaction->getTs(), $transactionResult->getTs());
    }

    public function testPostAddTransactionAction_IncompleteParameters_True()
    {
        $client = static::createClient();

        // Send receiver, timestamp and sum parameters.
        $crawler = $client->request('POST', '/transactions',
            array(
                "receiver" => 2,
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertContains("Parameters sender are missing.", $crawler->text());

        // Send sender, timestamp and sum parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertContains("Parameters receiver are missing.", $crawler->text());

        // Send sender, receiver and sum parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "sum" => 15001)
        );

        $this->assertContains("Parameters timestamp are missing.", $crawler->text());

        // Send sender, receiver and timestamp parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "timestamp" => 1491990709)
        );

        $this->assertContains("Parameters sum are missing.", $crawler->text());

        // Don't send any parameters.
        $crawler = $client->request('POST', '/transactions');

        $this->assertContains("Parameters sender, receiver, timestamp, sum are missing.", $crawler->text());
    }

    public function testPostAddTransactionAction_InvalidParameters_True()
    {
        $client = static::createClient();

        // Send receiver, timestamp and sum valid parameters and invalid sender parameter.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 'a',
                "receiver" => 2,
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertContains("Parameters sender are not integers.", $crawler->text());

        // Send sender, timestamp and sum valid parameters and invalid receiver parameter.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 'a',
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertContains("Parameters receiver are not integers.", $crawler->text());

        // Send sender, receiver and sum parameters and invalid timestamp parameter.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "timestamp" => 'a',
                "sum" => 15001)
        );

        $this->assertContains("Parameters timestamp are not integers.", $crawler->text());

        // Send sender, receiver and timestamp valid parameters and invalid sum parameter.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "timestamp" => 1491990709,
                "sum" => 'a')
        );

        $this->assertContains("Parameters sum are not integers.", $crawler->text());

        // All parameters are invalids.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 'a',
                "receiver" => 'b',
                "timestamp" => 'c',
                "sum" => 'd')
        );

        $this->assertContains("Parameters sender, receiver, timestamp, sum are not integers.", $crawler->text());
    }

    //
    // Tests for method getTransactionsAction
    //
    public function testGetTransactionsAction_AllValidParameters_True()
    {
        $client = static::createClient();

        // Add transactions
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 10,
                "receiver" => 2,
                "timestamp" => 1492360649,
                "sum" => 150)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 10,
                "receiver" => 2,
                "timestamp" => 1492360649,
                "sum" => 550)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 10,
                "receiver" => 2,
                "timestamp" => 1492360649,
                "sum" => 750)
        );

        // Send all parameters.
        $crawler = $client->request('GET', '/transactions/?user=10&day=1492360649&threshold=100');

        $this->assertNotContains("Parameters ",$crawler->text());
    }

    public function testGetTransactionsAction_IncompleteParameters_True()
    {
        $client = static::createClient();

        // Send day and threshold parameters.
        $crawler = $client->request('GET', '/transactions/?day=1492360649&threshold=100');

        $this->assertContains("Parameters user are missing.", $crawler->text());

        // Send user and day parameters.
        $crawler = $client->request('GET', '/transactions/?user=10&day=1492360649');

        $this->assertContains("Parameters threshold are missing.", $crawler->text());

        // Send user and threshold parameters.
        $crawler = $client->request('GET', '/transactions/?user=10&threshold=100');

        $this->assertContains("Parameters day are missing.", $crawler->text());

        // Don't send any parameters.
        $crawler = $client->request('GET', '/transactions/');

        $this->assertContains("Parameters user, day, threshold are missing.", $crawler->text());
    }

    public function testGetTransactionsAction_InvalidParameters_True()
    {
        $client = static::createClient();

        // Send day and threshold valid parameters and user invalid parameter.
        $crawler = $client->request('GET', '/transactions/?user=a&day=1492360649&threshold=100');

        $this->assertContains("Parameters user are not integers.", $crawler->text());

        // Send user and day valid parameters and threshold invalid parameter.
        $crawler = $client->request('GET', '/transactions/?user=10&day=1492360649&threshold=a');

        $this->assertContains("Parameters threshold are not integers.", $crawler->text());

        // Send user and threshold valid parameters and day invalid parameter.
        $crawler = $client->request('GET', '/transactions/?user=10&day=a&threshold=100');

        $this->assertContains("Parameters day are not integers.", $crawler->text());

        // All parameters are invalids.
        $crawler = $client->request('GET', '/transactions/?user=a&day=b&threshold=c');

        $this->assertContains("Parameters user, day, threshold are not integers.", $crawler->text());
    }

    public function testGetTransactionsAction_ValidQueries_ReturnTransactions()
    {
        $client = static::createClient();

        // Add transactions
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2000,
                "receiver" => 2060,
                "timestamp" => 1492453891,
                "sum" => 450)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2060,
                "receiver" => 2000,
                "timestamp" => 1492453903,
                "sum" => 150)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2051,
                "receiver" => 2000,
                "timestamp" => 1492538745,
                "sum" => 705)
        );

        // Return transactions
        $crawler = $client->request('GET', '/transactions/?user=2000&day=1492453903&threshold=150');

        $result = json_decode($crawler->text(),true);

        $transaction = new Transaction();
        $transaction->setSenderId(2000);
        $transaction->setReceiverId(2060);
        $transaction->setSum(450);
        $transaction->setTs(1492453891);

        $transactionResult = new Transaction();
        $transactionResult->setSenderId($result[0]["sender_id"]);
        $transactionResult->setReceiverId($result[0]["receiver_id"]);
        $transactionResult->setSum($result[0]["sum"]);
        $transactionResult->setTs($result[0]["ts"]);

        $this->assertEquals($transaction->getSenderId(), $transactionResult->getSenderId());
        $this->assertEquals($transaction->getReceiverId(), $transactionResult->getReceiverId());
        $this->assertEquals($transaction->getSum(), $transactionResult->getSum());
        $this->assertEquals($transaction->getTs(), $transactionResult->getTs());

        $transaction = new Transaction();
        $transaction->setSenderId(2060);
        $transaction->setReceiverId(2000);
        $transaction->setSum(150);
        $transaction->setTs(1492453903);

        $transactionResult = new Transaction();
        $transactionResult->setSenderId($result[1]["sender_id"]);
        $transactionResult->setReceiverId($result[1]["receiver_id"]);
        $transactionResult->setSum($result[1]["sum"]);
        $transactionResult->setTs($result[1]["ts"]);

        $this->assertEquals($transaction->getSenderId(), $transactionResult->getSenderId());
        $this->assertEquals($transaction->getReceiverId(), $transactionResult->getReceiverId());
        $this->assertEquals($transaction->getSum(), $transactionResult->getSum());
        $this->assertEquals($transaction->getTs(), $transactionResult->getTs());
    }

    public function testGetTransactionsAction_ValidQueries_NotReturnTransactions()
    {
        $client = static::createClient();

        // Add transactions
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2000,
                "receiver" => 2060,
                "timestamp" => 1492453891,
                "sum" => 450)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2060,
                "receiver" => 2000,
                "timestamp" => 1492453903,
                "sum" => 150)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2051,
                "receiver" => 2000,
                "timestamp" => 1492538745,
                "sum" => 705)
        );

        $crawler = $client->request('GET', '/transactions/?user=2001&day=1492453903&threshold=150');

        $result = json_decode($crawler->text(),true);
        $this->assertEquals(0,sizeof($result));

        $crawler = $client->request('GET', '/transactions/?user=2000&day=1492453903&threshold=15000');

        $result = json_decode($crawler->text(),true);
        $this->assertEquals(0,sizeof($result));

        $crawler = $client->request('GET', '/transactions/?user=2051&day=1392453903&threshold=150');

        $result = json_decode($crawler->text(),true);
        $this->assertEquals(0,sizeof($result));
    }

    //
    // Tests for method getBalanceAction
    //
    public function testGetBalanceAction_AllValidParameters_True()
    {
        $client = static::createClient();

        // Add transactions
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 20,
                "receiver" => 2,
                "timestamp" => 1492360649,
                "sum" => 150)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 20,
                "receiver" => 2,
                "timestamp" => 1492360649,
                "sum" => 550)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 20,
                "receiver" => 2,
                "timestamp" => 1492360649,
                "sum" => 750)
        );

        // Send all parameters.
        $crawler = $client->request('GET', '/balance/?user=20&since=1492360649&until=1492360649');

        $this->assertNotContains("Parameters ",$crawler->text());
    }

    public function testGetBalanceAction_IncompleteParameters_True()
    {
        $client = static::createClient();

        // Send since and until parameters.
        $crawler = $client->request('GET', '/balance/?since=1492360649&until=1492360649');

        $this->assertContains("Parameters user are missing.", $crawler->text());

        // Send user and since parameters.
        $crawler = $client->request('GET', '/balance/?user=10&since=1492360649');

        $this->assertContains("Parameters until are missing.", $crawler->text());

        // Send user and until parameters.
        $crawler = $client->request('GET', '/balance/?user=10&until=1492360649');

        $this->assertContains("Parameters since are missing.", $crawler->text());

        // Don't send any parameters.
        $crawler = $client->request('GET', '/balance/');

        $this->assertContains("Parameters user, since, until are missing.", $crawler->text());
    }

    public function testGetBalanceAction_InvalidParameters_True()
    {
        $client = static::createClient();

        // Send since and until valid parameters and user invalid parameter.
        $crawler = $client->request('GET', '/balance/?user=a&since=1492360649&until=1492360649');

        $this->assertContains("Parameters user are not integers.", $crawler->text());

        // Send user and since valid parameters and until invalid parameter.
        $crawler = $client->request('GET', '/balance/?user=10&since=1492360649&until=a');

        $this->assertContains("Parameters until are not integers.", $crawler->text());

        // Send user and until valid parameters and since invalid parameter.
        $crawler = $client->request('GET', '/balance/?user=10&since=a&until=1492360649');

        $this->assertContains("Parameters since are not integers.", $crawler->text());

        // All parameters are invalids.
        $crawler = $client->request('GET', '/balance/?user=a&since=b&until=c');

        $this->assertContains("Parameters user, since, until are not integers.", $crawler->text());
    }

    public function testGetBalanceAction_ValidQueries_ReturnBalanceNonZero()
    {
        $client = static::createClient();

        // Add transactions
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1995,
                "receiver" => 2017,
                "timestamp" => 1492453891,
                "sum" => 450)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2017,
                "receiver" => 2050,
                "timestamp" => 1492453903,
                "sum" => 150)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 2050,
                "receiver" => 2019,
                "timestamp" => 1492538745,
                "sum" => 75)
        );

        // Return balance
        $crawler = $client->request('GET', '/balance/?user=2019&since=1492453891&until=1492538745');

        $result = intval(json_decode($crawler->text()));
        $this->assertEquals(75, $result);

        $crawler = $client->request('GET', '/balance/?user=2017&since=1492453903&until=1492453903');

        $result = json_decode($crawler->text());
        $this->assertEquals(300, $result);

        $crawler = $client->request('GET', '/balance/?user=2050&since=1492453903&until=1492538745');

        $result = json_decode($crawler->text());
        $this->assertEquals(75, $result);
    }

    public function testGetBalanceAction_ValidQueries_ReturnBalanceZero()
    {
        $client = static::createClient();

        // Add transactions
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 3000,
                "receiver" => 3001,
                "timestamp" => 1492453891,
                "sum" => 1000)
        );

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 3001,
                "receiver" => 3000,
                "timestamp" => 1492453903,
                "sum" => 1000)
        );

        $crawler = $client->request('GET', '/balance/?user=2019&since=1492538745&until=1492453891');

        $result = intval(json_decode($crawler->text()));
        $this->assertEquals(0, $result);

        $crawler = $client->request('GET', '/balance/?user=2021&since=1492453891&until=1492538745');

        $result = intval(json_decode($crawler->text()));
        $this->assertEquals(0, $result);

        $crawler = $client->request('GET', '/balance/?user=3000&since=1492453891&until=1492453903');

        $result = intval(json_decode($crawler->text()));
        $this->assertEquals(0, $result);

        $crawler = $client->request('GET', '/balance/?user=3001&since=1492453891&until=1492453903');

        $result = intval(json_decode($crawler->text()));
        $this->assertEquals(0, $result);
    }
}