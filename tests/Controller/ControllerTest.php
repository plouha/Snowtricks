<?php
// tests/Controller/PostControllerTest.php
namespace App\Tests\Controller;

use Symfony\Component\HTTPFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{

    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        
        $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }

    public function testHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur SnowTricks !")')->count());
    }
    

}