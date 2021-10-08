<?php

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class SecurityControllerTest extends WebTestCase
{


    public function testLogin()
    {
        $client = new HttpBrowser(HttpClient::create());

        $crawler = $client->request('GET', 'http://projet8/public/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $data = ['_username' => 'user', '_password' => 'test'];
        $crawler = $client->submitForm('Se connecter', $data);

        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());
        
        // Return the client to reuse the authenticated user it in others functionnal tests
        return $client;
    }

    public function testLoginAsAdmin()
    {
        $client = new HttpBrowser(HttpClient::create());

        $crawler = $client->request('GET', 'http://projet8/public/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $data = ['_username' => 'admin', '_password' => 'test'];
        $crawler = $client->submitForm('Se connecter', $data);

        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        // Return the client to reuse the authenticated user it in others functionnal tests
        return $client;
    }

    public function testLoginWithWrongCredidentials()
    {
        $client = new HttpBrowser(HttpClient::create());

        $crawler = $client->request('GET', 'http://projet8/public/login');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $data = ['_username' => 'user', '_password' => 'wrongCredentials'];
        $crawler = $client->submitForm('Se connecter', $data);

        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if home page text when authenticated exists
        static::assertSame("Invalid credentials.", $crawler->filter('div.alert.alert-danger')->text());

        // Return the client to reuse the authenticated user it in others functionnal tests
        return $client;
    }
}