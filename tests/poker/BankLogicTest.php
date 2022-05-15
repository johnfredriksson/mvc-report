<?php

namespace App\Poker;

use PHPUnit\Framework\TestCase;
use App\Poker\BankLogic;

/**
 * Tests for class BankLogic
 */
class BankLogicTest extends TestCase
{
    /**
     * Test to get odds
     */
    public function testBankLogicGetOdds()
    {
        $bankLogic = new BankLogic();


        $this->assertEquals(true, is_int($bankLogic->getOdds()));
        $this->assertEquals(true, $bankLogic->getOdds() > 0);
        $this->assertEquals(true, $bankLogic->getOdds() <= 100);
    }

    /**
     * Test to get right choice from bank
     */
    public function testBankLogicBet()
    {
        $bankLogic = new BankLogic();


        $this->assertEquals(true, is_string($bankLogic->bet()));
        $this->assertEquals("fold", $bankLogic->bet(3));
        $this->assertEquals("check", $bankLogic->bet(36));
        $this->assertEquals("raise", $bankLogic->bet(89));
    }

    /**
     * Test to get right raise from bank
     */
    public function testBankLogicRaise()
    {
        $bankLogic = new BankLogic();
        $blind = 20;
        $player = 100;

        $this->assertEquals(true, is_int($bankLogic->raise($blind, $player)));

        $blind = 100;
        $player = 80;
        $this->assertEquals($player, $bankLogic->raise($blind, $player));
    }
}
