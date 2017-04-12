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
}
