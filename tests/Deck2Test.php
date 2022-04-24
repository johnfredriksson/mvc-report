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
}
