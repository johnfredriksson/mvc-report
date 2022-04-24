<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Tests for class Hand
 */
class HandTest extends TestCase
{
    /**
     * Test to create Hand object
     */
    public function testHandCreate()
    {
        $hand = new Hand([new Card("H", "7")]);

        $this->assertIsObject($hand);
    }

    /**
     * Test to fetch card from hand
     */
    public function testHandGetCards()
    {
        $hand = new Hand([new Card("H", "10")]);
        $cards = $hand->getCards();

        $this->assertEquals($cards[0]->getImgUrl(), "H10.png");
    }

    /**
     * Test to fetch card from hand
     */
    public function testHandAddCards()
    {
        $hand = new Hand([new Card("S", "K")]);
        $cards = $hand->getCards();

        $this->assertEquals($cards[0]->getImgUrl(), "SK.png");

        $hand->addCard([new Card("D", "5")]);
        $cards = $hand->getCards();

        $this->assertEquals(count($cards), 2);
        $this->assertEquals($cards[1]->getImgUrl(), "D5.png");
    }

    /**
     * Test to get sum with numbered cards
     */
    public function testHandGetSumNumberedCards()
    {
        $hand = new Hand([new Card("S", "6"), new Card("H", "5")]);

        $this->assertEquals($hand->getSum(), [11]);
    }

    /**
     * Test to get sum with dressed cards
     */
    public function testHandGetSumDressedCards()
    {
        $hand = new Hand([new Card("S", "K"), new Card("H", "Q")]);

        $this->assertEquals($hand->getSum(), [20]);
    }

    /**
     * Test to get sum with mixed cards
     */
    public function testHandGetSumMixedCards()
    {
        $hand = new Hand([new Card("S", "K"), new Card("H", "5")]);

        $this->assertEquals($hand->getSum(), [15]);
    }

    /**
     * Test to get sum with two aces
     */
    public function testHandGetSumTwoAces()
    {
        $hand = new Hand([new Card("S", "A"), new Card("H", "A")]);

        $this->assertEquals($hand->getSum(), [12, 2]);
    }

    /**
     * Test to get sum with ace and dressed
     */
    public function testHandGetSumAceAndDressed()
    {
        $hand = new Hand([new Card("S", "A"), new Card("H", "K")]);

        $this->assertEquals($hand->getSum(), [21, 11]);
    }

    /**
     * Test to get sum with ace and numbered
     */
    public function testHandGetSumAceAndNumbered()
    {
        $hand = new Hand([new Card("S", "A"), new Card("H", "5")]);

        $this->assertEquals($hand->getSum(), [16, 6]);
    }

    /**
     * Test to get sum with 3 cards
     */
    public function testHandGetSumThreeCards()
    {
        $hand = new Hand([new Card("S", "6"), new Card("D", "10"), new Card("S", "A")]);

        $this->assertEquals($hand->getSum(), [17]);
    }
}