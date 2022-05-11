<?php

namespace App\Poker;

use PHPUnit\Framework\TestCase;
use App\Game\Hand;
use App\Card\Card;

/**
 * Tests for class Rules
 */
class RulesTest extends TestCase
{
    /**
     * Test with a blackjack
     */
    public function testCreateNewHand()
    {
        
        $player = [new Card("H", "A"), new Card("D", "K")];
        $community = [new Card("H", "7"), new Card("D", "10"), new Card("C", "K"), new Card("D", "3"), new Card("D", "9")];
        $hand = new Rules($player, $community);

        $this->assertEquals($hand->getHand(), ["HA", "DK", "H7", "D10", "CK", "D3", "D9"]);
    }
}
