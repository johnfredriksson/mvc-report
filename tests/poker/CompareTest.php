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
        $community  = new Hand([new Card("H", "7"), new Card("D", "10"),
            new Card("C", "K"), new Card("D", "3"), new Card("D", "9")]);
        $playerHand = new Rules($player->getCards(), $community->getCards());
        $bankHand   = new Rules($bank->getCards(), $community->getCards());
        $rules      = new Compare($playerHand->getScore(), $bankHand->getScore());

        $this->assertEquals(["No One", "Draw"], $rules->compareHands());
    }

    /**
     * Test to compare player strong royal flush
     */
    public function testComparePlayerRoyalFlush()
    {
        $player     = new Hand([new Card("H", "J"), new Card("H", "10")]);
        $bank       = new Hand([new Card("H", "3"), new Card("D", "9")]);
        $community  = new Hand([new Card("H", "A"), new Card("H", "K"),
            new Card("H", "Q"), new Card("D", "3"), new Card("D", "9")]);
        $playerHand = new Rules($player->getCards(), $community->getCards());
        $bankHand   = new Rules($bank->getCards(), $community->getCards());
        $rules      = new Compare($playerHand->getScore(), $bankHand->getScore());

        $this->assertEquals(["You", "Royal Flush"], $rules->compareHands());
    }

    /**
     * Test to compare bank strong four of a kind
     */
    public function testCompareBankFourOfAKind()
    {
        $player     = new Hand([new Card("H", "10"), new Card("D", "10")]);
        $bank       = new Hand([new Card("S", "9"), new Card("C", "9")]);
        $community  = new Hand([new Card("H", "9"), new Card("D", "9"),
            new Card("H", "Q"), new Card("C", "10"), new Card("S", "7")]);
        $playerHand = new Rules($player->getCards(), $community->getCards());
        $bankHand   = new Rules($bank->getCards(), $community->getCards());
        $rules      = new Compare($playerHand->getScore(), $bankHand->getScore());

        $this->assertEquals(["Bank", "Four Of A Kind"], $rules->compareHands());
    }

    /**
     * Test to compare both three of a kind but player stronger
     */
    public function testCompareBothThreeOfAKindPlayerStrong()
    {
        $player     = new Hand([new Card("H", "10"), new Card("D", "10")]);
        $bank       = new Hand([new Card("S", "9"), new Card("C", "9")]);
        $community  = new Hand([new Card("H", "2"), new Card("D", "9"),
            new Card("H", "Q"), new Card("C", "10"), new Card("S", "7")]);
        $playerHand = new Rules($player->getCards(), $community->getCards());
        $bankHand   = new Rules($bank->getCards(), $community->getCards());
        $rules      = new Compare($playerHand->getScore(), $bankHand->getScore());

        $this->assertEquals(["You", "Three Of A Kind"], $rules->compareHands());
    }
}
