<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Tests for class Game
 */
class GameTest extends TestCase
{
    /**
     * Test to create Game object
     */
    public function testGameCreate()
    {
        $game = new Game(1337);

        $this->assertIsObject($game);
    }

    /**
     * Test to deal cards
     */
    public function testGameDealCards()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $this->assertIsObject($game->getPlayerObject());
        $this->assertIsObject($game->getDealerObject());
    }

    /**
     * Test to retrieve cards from hands
     */
    public function testGameGetCards()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $this->assertIsArray($game->getPlayer());
        $this->assertIsArray($game->getDealer());

        $this->assertEquals(count($game->getPlayer()), 2);
        $this->assertEquals(count($game->getDealer()), 2);
    }

    /**
     * Test to get balance
     */
    public function testGameGetBalance()
    {
        $game = new Game(1337);

        $this->assertEquals($game->getBalance(), 1337);
    }

    /**
     * Test to set balance with +
     */
    public function testGameSetBalanceAddition()
    {
        $game = new Game(1337);
        $game->setBalance(663, "+");

        $this->assertEquals($game->getBalance(), 2000);
    }

    /**
     * Test to set balance with -
     */
    public function testGameSetBalanceSubtraction()
    {
        $game = new Game(1337);
        $game->setBalance(337, "-");

        $this->assertEquals($game->getBalance(), 1000);
    }

    /**
     * Test to let player draw card
     */
    public function testGameDrawCardPlayer()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $this->assertEquals(count($game->getPlayer()), 2);
        $game->drawCardPlayer();
        $this->assertEquals(count($game->getPlayer()), 3);
    }

    /**
     * Test to let dealer draw card
     */
    public function testGameDrawCardDealer()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $this->assertEquals(count($game->getDealer()), 2);
        $game->drawCardDealer();
        $this->assertEquals(count($game->getDealer()), 3);
    }

    /**
     * Test to get players card faces
     */
    public function testGameGetPlayerCardFaces()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $hand = $game->getPlayer();
        $faces = $game->getCardFaces($hand);

        $this->assertStringContainsString(".png", $faces[0]);
        $this->assertStringContainsString(".png", $faces[1]);
    }

    /**
     * Test to get dealers card faces
     */
    public function testGameGetDealerCardFaces()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $hand = $game->getDealer();
        $faces = $game->getCardFaces($hand);

        $this->assertStringContainsString(".png", $faces[0]);
        $this->assertStringContainsString(".png", $faces[1]);
    }

    /**
     * Test to get players sum
     */
    public function testGameGetPlayerSum()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $hand = $game->getPlayerObject();
        $sum = $game->getSum($hand);

        $this->assertIsArray($sum);
        $this->assertIsInt($sum[0]);
    }

    /**
     * Test to get dealers sum
     */
    public function testGameGetDealerSum()
    {
        $game = new Game(1337);
        $game->setDeck();
        $game->dealCards();

        $hand = $game->getDealerObject();
        $sum = $game->getSum($hand);

        $this->assertIsArray($sum);
        $this->assertIsInt($sum[0]);
    }
}
