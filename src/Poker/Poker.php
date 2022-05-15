<?php

namespace App\Poker;

use App\Card\Deck;
use App\Game\Hand;

/**
 * Poker class to hold game logic for texas hold'em
 */
class Poker
{
    protected Deck $deck;
    protected Hand $player;
    protected Hand $bank;
    protected Hand $community;
    protected int $pot;
    /**
     * Constructor
     *
     * Builds a deck and shuffles it, also draws card for bank, player. sets pot to 0.
     */
    public function __construct()
    {
        $this->setDeck();
        $this->player = new Hand($this->deck->drawCardFull(2));
        $this->bank = new Hand($this->deck->drawCardFull(2));
        $this->pot = 0;
        $this->community = new Hand([]);
    }

    /**
     * Sets attribute deck to a new deck and shuffles it
     */
    public function setDeck()
    {
        $this->deck = new Deck();
        $this->deck->shuffleDeck();
    }

    /**
     * Draws three cards to community
     */
    public function flop()
    {
        $this->community->addCard($this->deck->drawCardFull(3));
    }

    /**
     * Draws one card to community
     */
    public function turn()
    {
        $this->community->addCard($this->deck->drawCardFull(1));
    }

    /**
     * Draws one card to community
     */
    public function river()
    {
        $this->community->addCard($this->deck->drawCardFull(1));
    }

    /**
     * Returns array of players card faces
     *
     * @return array containing players card faces
     */
    public function getPlayer()
    {
        return $this->getCardFaces($this->player->getCards());
    }

    /**
     * Returns array of banks card faces
     *
     * @return array containing banks card faces
     */
    public function getBank()
    {
        return $this->getCardFaces($this->bank->getCards());
    }

    /**
     * Returns array of community card faces
     *
     * @return array containing community card faces
     */
    public function getCommunity()
    {
        return $this->getCardFaces($this->community->getCards());
    }

    /**
     * Returns array of players card objects
     *
     * @return array containing players card objects
     */
    public function getPlayerFull()
    {
        // return $this->player->getCards();
        return $this->player->getCards();
    }

    /**
     * Returns array of banks card objects
     *
     * @return array containing banks card objects
     */
    public function getBankFull()
    {
        return $this->bank->getCards();
    }

    /**
     * Returns array of communitys card objects
     *
     * @return array containing communitys card objects
     */
    public function getCommunityFull()
    {
        return $this->community->getCards();
    }

    /**
     * Adds a amount to the total pot
     *
     * @param int $amount Amount to be added
     */
    public function addToPot(int $amount)
    {
        $this->pot += $amount;
    }

    /**
     * Returns the current pot
     *
     * @return int the pot total
     */
    public function getPot()
    {
        return $this->pot;
    }

    /**
     * Returns an array of image url's from cards in a hand
     */
    public function getCardFaces(array $hand): array
    {
        $res = [];

        foreach ($hand as $card) {
            array_push($res, $card->getImgUrl());
        }

        return $res;
    }
}
