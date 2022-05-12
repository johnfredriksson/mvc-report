<?php

namespace App\Poker;

use PHPUnit\Framework\TestCase;
use App\Game\Hand;
use App\Card\Card;
use App\Poker\Compare;
use App\Poker\Rules;

/**
 * Tests for class Compare
 */
class CompareTest extends TestCase
{
    /**
     * Test to compare equal hands
     */
    public function testCompareDraw()
    {
        
        $player     = new Hand([new Card("H", "A"), new Card("D", "K")]);
        $bank       = new Hand([new Card("H", "A"), new Card("D", "K")]);
        $community  = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "K"), new Card("D", "3"), new Card("D", "9")]);
        $playerHand = new Rules($player->getCards(), $community->getCards());
        $bankHand   = new Rules($bank->getCards(), $community->getCards());
        $rules      = new Compare($playerHand->getScore(), $bankHand->getScore());

        $this->assertEquals(["No One", "Draw"], $rules->compareHands());
    }


}
