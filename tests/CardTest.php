<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Create a card object with "H" and "9" parameters
     */
    public function testCardCreate()
    {
        $card = new Card("H", "9");

        $this->assertIsObject($card);
    }

    /**
     * Fetch value from card
     */
    public function testCardGetValue()
    {
        $card = new Card("C", "10");
        $card2 = new Card("D", "K");
        $card3 = new Card("H", "9");
        $card4 = new Card("S", "A");

        $this->assertEquals($card->getValue(), "10");
        $this->assertEquals($card2->getValue(), "K");
        $this->assertEquals($card3->getValue(), "9");
        $this->assertEquals($card4->getValue(), "A");
    }

    /**
     * Fetch image url from card
     */
    public function testCardGetImgUrl()
    {
        $card = new Card("H", "9");

        $this->assertEquals($card->getImgUrl(), "H9.png");
    }

    /**
     * Fetch card title
     */
    public function testCardGetTitle()
    {
        $card = new Card("H", "9");
        $title = $card->getObject()["title"];

        $this->assertEquals($title, "9 of Hearts");
    }
}
