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
    public function __construct()
    {
        $this->setDeck();
        $this->player = new Hand($this->deck->drawCardFull(2));
        $this->bank = new Hand($this->deck->drawCardFull(2));
        $this->pot = 0;
        $this->community = new Hand([]);
    }

    public function setDeck()
    {
        $this->deck = new Deck();
        $this->deck->shuffleDeck();
    }

    public function flop()
    {
        $this->community->addCard($this->deck->drawCardFull(3));
    }

    public function turn()
    {
        $this->community->addCard($this->deck->drawCardFull(1));
    }

    public function river()
    {
        $this->community->addCard($this->deck->drawCardFull(1));
    }

    public function getPlayer()
    {
        // return $this->player->getCards();
        return $this->getCardFaces($this->player->getCards());
    }

    public function getBank()
    {
        return $this->getCardFaces($this->bank->getCards());
    }

    public function getCommunity()
    {
        return $this->getCardFaces($this->community->getCards());    }

    public function addToPot(int $amount)
    {
        $this->pot += $amount;
    }

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