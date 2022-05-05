<?php

namespace App\Game;

use App\Card\Deck;
use App\Game\Hand;
use App\Game\Rules;

/**
 * Game class
 * 
 * Class holds all parts of a game session.
 * 
 * Deck - playing deck
 * Hand - holds a set of cards
 * Rules - basic rules of BlackJack
 */
class Game
{
    protected Deck $deck;
    protected int $balance;
    protected Hand $player;
    protected Hand $dealer;
    public Rules $rules;

    /**
     * Constructor
     * 
     * Set players balance to recieved parameter, 
     * initiate a deck and couple game rules
     */
    public function __construct(int $balance)
    {
        $this->balance = $balance;
        $this->setDeck();
        $this->rules = new \App\Game\Rules();
    }

    /**
     * Sets deck as a attribute and shuffles it, normally called 
     * between each round to start with a full and shuffled deck
     */
    public function setDeck(): void
    {
        $this->deck = new \App\Card\Deck();
        $this->deck->shuffleDeck();
    }

    /**
     * Draws two cards to each players/dealers hand
     */
    public function dealCards(): void
    {
        $this->player = new \App\Game\Hand($this->deck->drawCardFull(2));
        $this->dealer = new \App\Game\Hand($this->deck->drawCardFull(2));
    }

    /**
     * Returns player object
     */
    public function getPlayerObject(): Hand
    {
        return $this->player;
    }

    /**
     * Returns dealers object
     */
    public function getDealerObject(): Hand
    {
        return $this->dealer;
    }

    /**
     * Returns cards in players hand
     */
    public function getPlayer(): array
    {
        return $this->player->getCards();
    }

    /**
     * Returns cards in dealers hand
     */
    public function getDealer(): array
    {
        return $this->dealer->getCards();
    }

    /**
     * Returns current balance
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * Updates the balance. Based on win or loss the parameter 
     * "+" or "-" will be submited to change the balance in correct way
     */
    public function setBalance(int $amount, string $operator): void
    {
        if ($operator == "+") {
            $this->balance += $amount;
        }

        if ($operator == "-") {
            $this->balance -= $amount;
        }
    }

    /**
     * Draws a card to players hand
     */
    public function drawCardPlayer(): void
    {
        $this->player->addCard($this->deck->drawCardFull(1));
    }

    /**
     * Draws a card to dealers hand
     */
    public function drawCardDealer(): void
    {
        $this->dealer->addCard($this->deck->drawCardFull(1));
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

    /**
     * Returns the sum from cards in hand
     */
    public function getSum(Hand $hand): array
    {
        return $hand->getSum();
    }
}
