<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/04/18
 * Time: 15:26
 */

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function RefreshTokenWithout()
    {
        $client = static::createClient();
        $client->request('GET', '/api/refreshToken');
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}