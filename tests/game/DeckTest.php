<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class deck.
 */
class DeckTest extends TestCase
{
    /**
     * Test to create deck and check if its an object
     */
    public function testDeckCreate()
    {
        $deck = new Deck();

        $this->assertIsObject($deck);
    }

    /**
     * Test if getDeck() returns an array of 52 objects
     */
    public function testDeckGetDeck()
    {
        $deck = new Deck();
        $cards = $deck->getDeck();

        $this->assertEquals(count($cards), 52);
        $this->assertIsObject($cards[0]);
    }

    /**
     * Test to draw a single card two times.
     * 
     * Checks if card gets correct value and 
     * deck gets smaller
     */
    public function testDeckDrawSingleCard()
    {
        $deck = new Deck();
        $hand = $deck->drawCard(1);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0], "CA.png");
        $this->assertEquals(count($cards), 51);

        $hand = $deck->drawCard(1);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0], "C2.png");
        $this->assertEquals(count($cards), 50);
    }

    /**
     * Test to draw multiple cards two times.
     * 
     * Checks if cards gets correct values and 
     * deck gets smaller
     */
    public function testDeckDrawMultipleCards()
    {
        $deck = new Deck();
        $hand = $deck->drawCard(3);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0], "CA.png");
        $this->assertEquals($hand[1], "C2.png");
        $this->assertEquals($hand[2], "C3.png");
        $this->assertEquals(count($cards), 49);

        $hand = $deck->drawCard(5);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0], "C4.png");
        $this->assertEquals($hand[1], "C5.png");
        $this->assertEquals($hand[2], "C6.png");
        $this->assertEquals($hand[3], "C7.png");
        $this->assertEquals($hand[4], "C8.png");

        $this->assertEquals(count($cards), 44);
    }

    /**
     * Test to draw from empty deck
     * 
     */
    public function testDeckDrawEmpyDeck()
    {
        $deck = new Deck();
        $hand = $deck->drawCard(52);
        $cards = $deck->getDeck();

        $this->assertEquals(count($cards), 0);

        $hand = $deck->drawCard(1);
        $this->assertEquals(count($hand), 0);
    }

    /**
     * Test to draw a single card two times.
     * 
     * Checks if card gets correct value and 
     * deck gets smaller
     */
    public function testDeckDrawSingleCardObject()
    {
        $deck = new Deck();
        $hand = $deck->drawCardFull(1);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0]->getImgUrl(), "CA.png");
        $this->assertEquals(count($cards), 51);

        $hand = $deck->drawCardFull(1);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0]->getImgUrl(), "C2.png");
        $this->assertEquals(count($cards), 50);
    }

    /**
     * Test to draw multiple cards two times.
     * 
     * Checks if cards gets correct values and 
     * deck gets smaller
     */
    public function testDeckDrawMultipleCardsObject()
    {
        $deck = new Deck();
        $hand = $deck->drawCardFull(3);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0]->getImgUrl(), "CA.png");
        $this->assertEquals($hand[1]->getImgUrl(), "C2.png");
        $this->assertEquals($hand[2]->getImgUrl(), "C3.png");
        $this->assertEquals(count($cards), 49);

        $hand = $deck->drawCardFull(5);
        $cards = $deck->getDeck();

        $this->assertEquals($hand[0]->getImgUrl(), "C4.png");
        $this->assertEquals($hand[1]->getImgUrl(), "C5.png");
        $this->assertEquals($hand[2]->getImgUrl(), "C6.png");
        $this->assertEquals($hand[3]->getImgUrl(), "C7.png");
        $this->assertEquals($hand[4]->getImgUrl(), "C8.png");

        $this->assertEquals(count($cards), 44);
    }

    /**
     * Test to draw from empty deck
     * 
     */
    public function testDeckDrawEmpyDeckObject()
    {
        $deck = new Deck();
        $hand = $deck->drawCardFull(52);
        $cards = $deck->getDeck();

        $this->assertEquals(count($cards), 0);

        $hand = $deck->drawCardFull(1);
        $this->assertEquals(count($hand), 0);
    }

    /**
     * Test to shuffle deck
     */
    public function testDeckShuffle()
    {
        $deck = new Deck();
        $deckShuffled = new Deck();
        $deckShuffled->shuffleDeck();

        $deck->getDeck();
        $deckShuffled->getDeck();

        $this->assertNotEquals($deck, $deckShuffled);
    }

    /**
     * Test to fetch number of cards left in deck
     */
    public function testDeckCount()
    {
        $deck = new Deck();
        $this->assertEquals($deck->countDeck(), 52);

        $deck->drawCard(10);
        $this->assertEquals($deck->countDeck(), 42);
    }

    /**
     * Test to fetch json data
     */
    public function testDeckGetJson()
    {
        $deck = new Deck();
        $deckJson = $deck->getJson();

        $this->assertStringContainsString('"suit": "C"', $deckJson);
        $this->assertStringContainsString('"value": "4"', $deckJson);
        $this->assertStringContainsString('"img": "C7"', $deckJson);
        $this->assertStringContainsString('"title": "9 of Diamonds"', $deckJson);
    }
}