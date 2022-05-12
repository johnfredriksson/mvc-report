<?php

namespace App\Poker;

use PHPUnit\Framework\TestCase;
use App\Game\Hand;
use App\Card\Card;
use App\Poker\Rules;

/**
 * Tests for class Rules
 */
class RulesTest extends TestCase
{
    /**
     * Test to create a new hand in rules
     */
    public function testCreateNewHand()
    {
        
        $player = new Hand([new Card("H", "A"), new Card("D", "K")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "K"), new Card("D", "3"), new Card("D", "9")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(["H14", "D13", "H7", "D10", "C13", "D3", "D9"], $hand->getHand());
    }

    /**
     * Test to retrieve all suits from hand
     */
    public function testGetSuits()
    {
        
        $player = new Hand([new Card("H", "A"), new Card("D", "K")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "K"), new Card("D", "3"), new Card("D", "9")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(["H", "D", "H", "D", "C", "D", "D"], $hand->getSuits());
    }

    /**
     * Test to retrieve all values from hand
     */
    public function testGetValues()
    {
        
        $player = new Hand([new Card("H", "A"), new Card("D", "K")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "K"), new Card("D", "3"), new Card("D", "9")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(["14", "13", "7", "10", "13", "3", "9"], $hand->getValues());
    }

    /**
     * Test to confirm a four of a kind with aces
     */
    public function testFourOfAKindTrueAces()
    {
        
        $player = new Hand([new Card("H", "A"), new Card("D", "A")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "A"), new Card("D", "3"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->fourOfAKind());
    }

    /**
     * Test to confirm a four of a kind with 4's
     */
    public function testFourOfAKindTrueFours()
    {
        
        $player = new Hand([new Card("H", "4"), new Card("D", "4")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "4"), new Card("D", "3"), new Card("S", "4")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->fourOfAKind());
    }

    /**
     * Test to confirm a four of a kind with 2's
     */
    public function testFourOfAKindTrueTwos()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "2")]);
        $community = new Hand([new Card("H", "2"), new Card("D", "A"), new Card("C", "A"), new Card("D", "Q"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->fourOfAKind());
    }

    /**
     * Test to confirm a false four of a kind
     */
    public function testFourOfAKindFalse()
    {
        
        $player = new Hand([new Card("H", "A"), new Card("D", "A")]);
        $community = new Hand([new Card("C", "A"), new Card("D", "10"), new Card("C", "10"), new Card("H", "10"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->fourOfAKind());
    }

    /**
     * Test to confirm a three of a kind with aces
     */
    public function testTheeOfAKindTrueAces()
    {
        
        $player = new Hand([new Card("H", "A"), new Card("D", "A")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "A"), new Card("D", "3"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->threeOfAKind());
    }

    /**
     * Test to confirm a three of a kind with 9's
     */
    public function testThreeOfAKindTrueNines()
    {
        
        $player = new Hand([new Card("H", "9"), new Card("D", "9")]);
        $community = new Hand([new Card("H", "7"), new Card("D", "10"), new Card("C", "9"), new Card("D", "3"), new Card("S", "7")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->threeOfAKind());
    }

    /**
     * Test to confirm a three of a kind with 2's
     */
    public function testThreeOfAKindTrueTwos()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "2")]);
        $community = new Hand([new Card("H", "2"), new Card("D", "A"), new Card("C", "A"), new Card("D", "Q"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->threeOfAKind());
    }

    /**
     * Test to confirm a false three of a kind
     */
    public function testThreeOfAKindFalse()
    {
        
        $player = new Hand([new Card("H", "Q"), new Card("D", "5")]);
        $community = new Hand([new Card("C", "A"), new Card("D", "10"), new Card("C", "9"), new Card("H", "10"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->threeOfAKind());
    }

    /**
     * Test to confirm a pair of queens
     */
    public function testPairTrueQueens()
    {
        
        $player = new Hand([new Card("H", "Q"), new Card("D", "5")]);
        $community = new Hand([new Card("C", "A"), new Card("D", "Q"), new Card("C", "10"), new Card("H", "10"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->pair());
        $this->assertEquals(12, $hand->pair());
    }

    /**
     * Test to confirm a pair of fives
     */
    public function testPairTrueFives()
    {
        
        $player = new Hand([new Card("H", "Q"), new Card("D", "5")]);
        $community = new Hand([new Card("C", "5"), new Card("C", "5"), new Card("C", "J"), new Card("H", "10"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->pair());
        $this->assertEquals(5, $hand->pair());
    }

    /**
     * Test to confirm a pair of aces
     */
    public function testPairTrueAces()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "2")]);
        $community = new Hand([new Card("C", "A"), new Card("D", "Q"), new Card("C", "2"), new Card("H", "10"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->pair());
        $this->assertEquals(14, $hand->pair());
    }

    /**
     * Test to confirm a false pair
     */
    public function testPairFalse()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "3")]);
        $community = new Hand([new Card("C", "8"), new Card("D", "Q"), new Card("C", "5"), new Card("H", "10"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->pair());
        $this->assertEquals(0, $hand->pair());
    }

    /**
     * Test to confirm a flush with 5 matching
     */
    public function testFlushTrueQueens()
    {
        
        $player = new Hand([new Card("C", "Q"), new Card("C", "5")]);
        $community = new Hand([new Card("C", "A"), new Card("D", "Q"), new Card("C", "J"), new Card("C", "10"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->flush());
        $this->assertEquals(1, $hand->flush());
    }

    /**
     * Test to confirm a flush with 6 matching
     */
    public function testFlushTrueFives()
    {
        
        $player = new Hand([new Card("H", "Q"), new Card("H", "5")]);
        $community = new Hand([new Card("H", "5"), new Card("H", "5"), new Card("H", "J"), new Card("H", "10"), new Card("S", "2")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->flush());
        $this->assertEquals(1, $hand->flush());
    }

    /**
     * Test to confirm a flush with 7 matching
     */
    public function testFlushTrueAces()
    {
        
        $player = new Hand([new Card("H", "5"), new Card("H", "4")]);
        $community = new Hand([new Card("H", "3"), new Card("H", "Q"), new Card("H", "2"), new Card("H", "10"), new Card("H", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->flush());
        $this->assertEquals(1, $hand->flush());
    }

    /**
     * Test to confirm a false flush
     */
    public function testFlushFalse()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "8"), new Card("D", "Q"), new Card("S", "5"), new Card("D", "10"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->flush());
        $this->assertEquals(0, $hand->flush());
    }

    /**
     * Test to confirm a straight 10, J, Q, K, A
     */
    public function testStraightTrueAceHigh()
    {
        
        $player = new Hand([new Card("H", "Q"), new Card("D", "J")]);
        $community = new Hand([new Card("D", "K"), new Card("D", "10"), new Card("S", "A"), new Card("D", "7"), new Card("S", "8")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->straight());
        $this->assertEquals(14, $hand->straight());
    }

    /**
     * Test to confirm a straight 5,6,7,8,9
     */
    public function testStraightTrueNineHigh()
    {
        
        $player = new Hand([new Card("H", "5"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "K"), new Card("D", "6"), new Card("S", "7"), new Card("D", "9"), new Card("S", "8")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->straight());
        $this->assertEquals(9, $hand->straight());
    }

    /**
     * Test to confirm a straight 2,3,4,5,6
     */
    public function testStraightTrueSixHigh()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "4"), new Card("D", "6"), new Card("S", "5"), new Card("D", "K"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->straight());
        $this->assertEquals(6, $hand->straight());
    }

    /**
     * Test to confirm a straight A,2,3,4,5
     */
    public function testStraightTrueFiveHigh()
    {
        
        $player = new Hand([new Card("H", "2"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "4"), new Card("D", "A"), new Card("S", "5"), new Card("D", "K"), new Card("S", "A")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->straight());
        $this->assertEquals(5, $hand->straight());
    }
    
    /**
     * Test to confirm a straight 7,8,9,10,11
     */
    public function testStraightTrueFJackHigh()
    {
        
        $player = new Hand([new Card("H", "7"), new Card("D", "9")]);
        $community = new Hand([new Card("D", "8"), new Card("D", "10"), new Card("S", "J"), new Card("H", "10"), new Card("S", "10")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->straight());
        $this->assertEquals(11, $hand->straight());
    }

    /**
     * Test to confirm a false straight 
     */
    public function testStraightFalse()
    {
        
        $player = new Hand([new Card("H", "5"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "K"), new Card("D", "Q"), new Card("S", "7"), new Card("D", "9"), new Card("S", "8")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->straight());
        $this->assertEquals(0, $hand->straight());
    }

    /**
     * Test to confirm a two pair 2,10
     */
    public function testTwoPairTrueTwosTens()
    {
        $player = new Hand([new Card("H", "2"), new Card("D", "10")]);
        $community = new Hand([new Card("D", "K"), new Card("D", "2"), new Card("S", "7"), new Card("H", "10"), new Card("S", "8")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->twoPair());
        $this->assertEquals(10, $hand->twoPair());
    }

    /**
     * Test to confirm a two pair A,7
     */
    public function testTwoPairTrueAcesSevens()
    {
        $player = new Hand([new Card("H", "A"), new Card("D", "A")]);
        $community = new Hand([new Card("D", "7"), new Card("D", "A"), new Card("S", "7"), new Card("H", "10"), new Card("S", "8")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->twoPair());
        $this->assertEquals(14, $hand->twoPair());
    }

    /**
     * Test to confirm a two pair A,7
     */
    public function testTwoPairFalse()
    {
        $player = new Hand([new Card("H", "Q"), new Card("D", "2")]);
        $community = new Hand([new Card("D", "7"), new Card("D", "A"), new Card("S", "5"), new Card("H", "10"), new Card("S", "8")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->twoPair());
        $this->assertEquals(0, $hand->twoPair());
    }

    /**
     * Test to confirm a three of a kind A,7
     */
    public function testFullHouseTrueAcesSevens()
    {
        $player = new Hand([new Card("H", "A"), new Card("D", "A")]);
        $community = new Hand([new Card("D", "10"), new Card("D", "A"), new Card("S", "7"), new Card("H", "3"), new Card("S", "7")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->fullHouse());
        $this->assertEquals(14, $hand->fullHouse());
    }

    /**
     * Test to confirm a three of a kind three kings and three 10's
     */
    public function testFullHouseTrueKingsTens()
    {
        $player = new Hand([new Card("H", "K"), new Card("D", "Q")]);
        $community = new Hand([new Card("D", "10"), new Card("D", "K"), new Card("S", "10"), new Card("H", "K"), new Card("S", "10")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->fullHouse());
        $this->assertEquals(13, $hand->fullHouse());
    }

    /**
     * Test to confirm a three of a kind four 4s and three 3s
     */
    public function testFullHouseTrueFoursThrees()
    {
        $player = new Hand([new Card("H", "4"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "3"), new Card("D", "4"), new Card("S", "3"), new Card("H", "4"), new Card("S", "4")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->fullHouse());
        $this->assertEquals(4, $hand->fullHouse());
    }

    /**
     * Test to confirm a false three of a kind
     */
    public function testFullHouseFalse()
    {
        $player = new Hand([new Card("H", "4"), new Card("D", "3")]);
        $community = new Hand([new Card("D", "3"), new Card("D", "4"), new Card("S", "K"), new Card("H", "Q"), new Card("S", "J")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(false, $hand->fullHouse());
        $this->assertEquals(0, $hand->fullHouse());
    }

    /**
     * Test to retrieve high card array
     */
    public function testHighCard()
    {
        $player = new Hand([new Card("H", "4"), new Card("D", "2")]);
        $community = new Hand([new Card("D", "3"), new Card("D", "4"), new Card("S", "K"), new Card("H", "Q"), new Card("S", "J")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals($hand->highCard(), ["13", "12", "11", "4", "4", "3", "2"]);
    }

    /**
     * Test to confirm royal flush
     */
    public function testRoyalFlushTrue()
    {
        $player = new Hand([new Card("H", "K"), new Card("H", "10")]);
        $community = new Hand([new Card("D", "3"), new Card("H", "A"), new Card("S", "K"), new Card("H", "Q"), new Card("H", "J")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $this->assertEquals(true, $hand->royalFlush());
    }

    /**
     * Test to retrive scoreboard
     */
    public function testScoreBoard()
    {
        $player = new Hand([new Card("H", "K"), new Card("H", "10")]);
        $community = new Hand([new Card("D", "3"), new Card("H", "2"), new Card("S", "2"), new Card("H", "Q"), new Card("H", "9")]);
        $hand = new Rules($player->getCards(), $community->getCards());

        $scoreboard = [
            "royalFlush"     => 0,
            "straightFlush"  => 0,
            "fourOfAKind"    => 0,
            "fullHouse"      => 0,
            "flush"          => 1,
            "straight"       => 0,
            "threeOfAKind"   => 0,
            "twoPair"        => 0,
            "pair"           => 2,
            "highCard"       => ["13", "12", "10", "9", "3", "2", "2"]
        ];

        $this->assertEquals($scoreboard, $hand->getScore());
    }

}
