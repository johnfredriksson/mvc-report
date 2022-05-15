<?php

namespace App\Poker;

use PHPUnit\Framework\TestCase;
use App\Poker\Poker;
use App\Card\Card;

/**
 * Tests for class Poker
 */
class PokerTest extends TestCase
{
    /**
     * Test to create Poker
     */
    public function testPokerCreate()
    {
        $poker = new poker();


        $this->assertInstanceOf(Poker::class, $poker);
    }

    /**
     * Test to fetch player cards
     */
    public function testPokerGetPlayer()
    {
        $poker = new poker();

        $this->assertEquals(2, count($poker->getPlayer()));
    }

    /**
     * Test to fetch bank cards
     */
    public function testPokerGetBank()
    {
        $poker = new poker();

        $this->assertEquals(2, count($poker->getBank()));
    }

    /**
     * Test community cards on all stages
     */
    public function testPokerCommunityStages()
    {
        $poker = new poker();
        $this->assertEquals(0, count($poker->getCommunity()));

        $poker->flop();
        $this->assertEquals(3, count($poker->getCommunity()));

        $poker->turn();
        $this->assertEquals(4, count($poker->getCommunity()));

        $poker->river();
        $this->assertEquals(5, count($poker->getCommunity()));
    }

    /**
     * Test to fetch player full cards
     */
    public function testPokerGetPlayerFull()
    {
        $poker = new poker();


        $this->assertInstanceOf(Card::class, $poker->getPlayerFull()[0]);
        $this->assertInstanceOf(Card::class, $poker->getPlayerFull()[1]);
    }

    /**
     * Test to fetch bank full card
     */
    public function testPokerGetBankFull()
    {
        $poker = new poker();


        $this->assertInstanceOf(Card::class, $poker->getBankFull()[0]);
        $this->assertInstanceOf(Card::class, $poker->getBankFull()[1]);
    }

    /**
     * Test to fetch community full cards
     */
    public function testPokerGetCommunityFull()
    {
        $poker = new poker();
        $poker->flop();

        $this->assertInstanceOf(Card::class, $poker->getCommunityFull()[0]);
        $this->assertInstanceOf(Card::class, $poker->getCommunityFull()[1]);
        $this->assertInstanceOf(Card::class, $poker->getCommunityFull()[2]);
    }

    /**
     * Test to edit pot
     */
    public function testPokerPot()
    {
        $poker = new poker();

        $this->assertEquals(0, $poker->getPot());

        $poker->addToPot(100);
        $this->assertEquals(100, $poker->getPot());
    }
}
