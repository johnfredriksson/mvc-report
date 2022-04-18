<?php

namespace App\Game;

use App\Card\Deck;
use App\Game\Hand;
use App\Game\Rules;

class Game
{
    protected Deck $deck;
    protected int $balance;
    protected Hand $player;
    protected Hand $dealer;
    public Rules $rules;

    public function __construct(int $balance)
    {
        $this->balance = $balance;
        $this->setDeck();
        $this->rules = new \App\Game\Rules();
    }

    public function setDeck(): void
    {
        $this->deck = new \App\Card\Deck();
        $this->deck->shuffleDeck();
    }

    public function dealCards(): void
    {
        $this->player = new \App\Game\Hand($this->deck->drawCardFull(2));
        $this->dealer = new \App\Game\Hand($this->deck->drawCardFull(2));
    }

    public function getPlayerObject(): Hand
    {
        return $this->player;
    }

    public function getDealerObject(): Hand
    {
        return $this->dealer;
    }

    public function getPlayer(): array
    {
        return $this->player->getCards();
    }

    public function getDealer(): array
    {
        return $this->dealer->getCards();
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $amount, string $operator): void
    {
        if ($operator == "+") {
            $this->balance += $amount;
        }

        if ($operator == "-") {
            $this->balance -= $amount;
        }
    }

    public function drawCardPlayer(): void
    {
        $this->player->addCard($this->deck->drawCardFull(1));
    }

    public function drawCardDealer(): void
    {
        $this->dealer->addCard($this->deck->drawCardFull(1));
    }

    public function getCardFaces(array $hand): array
    {
        $res = [];

        foreach ($hand as $card) {
            array_push($res, $card->getImgUrl());
        }

        return $res;
    }

    public function getSum(Hand $hand): array
    {
        return $hand->getSum();
    }
}
