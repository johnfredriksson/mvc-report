<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Deck 2.
 */
class Deck2Test extends TestCase
{
    /**
     * Test to create an object
     */
    public function testDeck2Create()
    {
        $deck = new Deck2();

        $this->assertIsObject($deck);
    }

    /**
     * Test is Jokers are added correctly
     */
    public function testDeck2AddJoker()
    {
        $deck = new Deck2();
        $deck->addJoker();
        $cards = $deck->getDeck();

        $this->assertEquals($deck->countDeck(), 54);
        $this->assertEquals($cards[52]->getImgUrl(), "BJ.png");
        $this->assertEquals($cards[53]->getImgUrl(), "RJ.png");
    }
}
