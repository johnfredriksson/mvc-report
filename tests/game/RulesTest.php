<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;
use App\Card\Card;

/**
 * Tests for class Rules
 */
class RulesTest extends TestCase
{
    /**
     * Test with a blackjack
     */
    public function testRulesBlackJackTrue()
    {
        $rules = new Rules();
        $cards = [new Card("H", "A"), new Card("D", "K")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->blackjack($hand), true);
    }

    /**
     * Test with no blackjack
     */
    public function testRulesBlackJackFalse()
    {
        $rules = new Rules();
        $cards = [new Card("H", "2"), new Card("D", "K")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->blackjack($hand), false);
    }

    /**
     * Test with sum 21 with one card
     */
    public function testRulesBlackJackOneCard()
    {
        $rules = new Rules();
        $cards = [new Card("H", "21")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->blackjack($hand), false);
    }

    /**
     * Test with sum 21 with three cards
     */
    public function testRulesBlackJackThreeCards()
    {
        $rules = new Rules();
        $cards = [new Card("H", "5"), new Card("D", "K"), new Card("D", "6")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->blackjack($hand), false);
    }

    /**
     * Test with less than 21
     */
    public function testRulesFatFalse()
    {
        $rules = new Rules();
        $cards = [new Card("H", "5"), new Card("D", "K")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->fat($hand), false);
    }

    /**
     * Test with greater than 21
     */
    public function testRulesFatTrue()
    {
        $rules = new Rules();
        $cards = [new Card("H", "5"), new Card("D", "K"), new Card("S", "8")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->fat($hand), true);
    }

    /**
     * Test with 21
     */
    public function testRulesFatMax()
    {
        $rules = new Rules();
        $cards = [new Card("H", "A"), new Card("D", "K")];
        $hand = new Hand($cards);

        $this->assertEquals($rules->fat($hand), false);
    }
}
