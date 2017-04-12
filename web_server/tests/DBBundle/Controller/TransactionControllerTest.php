<?php

namespace DBBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransactionControllerTest extends WebTestCase
{
    public function testPostAddTransactionAction_AllValidParameters_True()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                  "receiver" => 2,
                  "timestamp" => 1491990709,
                   "sum" => 15001)
        );

        $this->assertEquals(1, $crawler->filter('html:contains("The transaction was successfully added!")')->count());
    }

    public function testPostAddTransactionAction_IncompleteParameters_True()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/transactions',
            array(
                "receiver" => 2,
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertEquals(1, $crawler->filter('html:contains("Incomplete payload! Parameters sender are missing.")')->count());

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "timestamp" => 1491990709,
                "sum" => 15001)
        );

        $this->assertEquals(1, $crawler->filter('html:contains("Incomplete payload! Parameters receiver are missing.")')->count());

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "sum" => 15001)
        );

        $this->assertEquals(1, $crawler->filter('html:contains("Incomplete payload! Parameters timestamp are missing.")')->count());

        $crawler = $client->request('POST', '/transactions',
            array("sender" => 1,
                "receiver" => 2,
                "timestamp" => 1491990709)
        );

        $this->assertEquals(1, $crawler->filter('html:contains("Incomplete payload! Parameters sum are missing.")')->count());

        $crawler = $client->request('POST', '/transactions');

        $this->assertEquals(1, $crawler->filter('html:contains("Incomplete payload! Parameters sender, receiver, timestamp, sum are missing.")')->count());
    }
}
