<?php

namespace App\Game;

use App\Card\Deck;
use App\Game\Hand;
use App\Game\Rules;


class Game {
    protected $deck;
    protected $balance;
    protected $player;
    protected $dealer;
    public $rules;

    public function __construct($balance)
    {
        $this->balance = $balance;
        $this->setDeck();
        $this->rules = new Rules();
    }

    public function setDeck()
    {
        $this->deck = new Deck();
        $this->deck->shuffleDeck();

    }

    public function dealCards()
    {
        $this->player = new Hand($this->deck->drawCardFull(2));
        $this->dealer = new Hand($this->deck->drawCardFull(2));
    }

    public function getPlayerObject()
    {
        return $this->player;
    }

    public function getDealerObject()
    {
        return $this->dealer;
    }

    public function getPlayer()
    {
        return $this->player->getCards();
    }

    public function getDealer()
    {
        return $this->dealer->getCards();
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($amount, $operator)
    {
        if ($operator == "+") {
            $this->balance += $amount;
            return;
        }
        $this->balance -= $amount;
    }

    public function drawCardPlayer()
    {
        $this->player->addCard($this->deck->drawCardFull(1));
    }

    public function drawCardDealer()
    {
        $this->dealer->addCard($this->deck->drawCardFull(1));
    }

    public function getCardFaces($hand)
    {
        $res = [];

        foreach ($hand as $card) {
            array_push($res, $card->getImgUrl());
        }

        return $res;
    }
    
    public function getSum($hand) {
        return $hand->getSum();
    }
}