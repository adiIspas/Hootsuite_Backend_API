<?php

namespace DBBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
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

        $this->assertContains("Incomplete payload! Parameters sender are missing.", $crawler->text());

        // Send sender, timestamp and sum parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertContains("Incomplete payload! Parameters receiver are missing.", $crawler->text());

        // Send sender, receiver and sum parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "sum" => 15001)
        );

        $this->assertContains("Incomplete payload! Parameters timestamp are missing.", $crawler->text());

        // Send sender, receiver and timestamp parameters.
        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "timestamp" => 1491990709)
        );

        $this->assertContains("Incomplete payload! Parameters sum are missing.", $crawler->text());

        // Don't send any parameters.
        $crawler = $client->request('POST', '/transactions');

        $this->assertContains("Incomplete payload! Parameters sender, receiver, timestamp, sum are missing.", $crawler->text());
    }

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

        $this->assertNotContains("Incomplete payload! Parameters",$crawler->text());
    }

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

        $this->assertNotContains("Incomplete payload! Parameters",$crawler->text());
    }

    public function testGetTransactionsAction_IncompleteParameters_True()
    {
        $client = static::createClient();

        // Send day and threshold parameters.
        $crawler = $client->request('GET', '/transactions/?day=1492360649&threshold=100');

        $this->assertContains("Incomplete payload! Parameters user are missing.", $crawler->text());

        // Send user and day parameters.
        $crawler = $client->request('GET', '/transactions/?user=10&day=1492360649');

        $this->assertContains("Incomplete payload! Parameters threshold are missing.", $crawler->text());

        // Send user and threshold parameters.
        $crawler = $client->request('GET', '/transactions/?user=10&threshold=100');

        $this->assertContains("Incomplete payload! Parameters day are missing.", $crawler->text());

        // Don't send any parameters.
        $crawler = $client->request('GET', '/transactions/');

        $this->assertContains("Incomplete payload! Parameters user, day, threshold are missing.", $crawler->text());
    }

    public function testGetBalanceAction_IncompleteParameters_True()
    {
        $client = static::createClient();

        // Send since and until parameters.
        $crawler = $client->request('GET', '/balance/?since=1492360649&until=1492360649');

        $this->assertContains("Incomplete payload! Parameters user are missing.", $crawler->text());

        // Send user and since parameters.
        $crawler = $client->request('GET', '/balance/?user=10&since=1492360649');

        $this->assertContains("Incomplete payload! Parameters until are missing.", $crawler->text());

        // Send user and until parameters.
        $crawler = $client->request('GET', '/balance/?user=10&until=1492360649');

        $this->assertContains("Incomplete payload! Parameters since are missing.", $crawler->text());

        // Don't send any parameters.
        $crawler = $client->request('GET', '/balance/');

        $this->assertContains("Incomplete payload! Parameters user, since, until are missing.", $crawler->text());
    }
}