<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for CardController
 */
class CardControllerTest extends WebTestCase
{
    /**
     * Tests landing page
     */
    public function testHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/card');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Card');
    }

    /**
     * Tests deck
     */
    public function testDeck(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/card/deck');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Deck');
    }

    /**
     * Tests shuffle
     */
    public function testShuffle(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/card/deck/shuffle');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Deck');
    }

    /**
     * Tests draw
     */
    public function testDraw(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/card/deck/draw');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Card');
        $this->assertSelectorTextContains('p', 'Cards left: 51');
    }

    /**
     * Tests draw:number
     */
    public function testDrawNumber(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/card/deck/draw/4');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Card');
        $this->assertSelectorTextContains('p', 'Cards left: 48');
    }
}
