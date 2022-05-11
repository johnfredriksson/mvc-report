<?php

namespace App\Poker;
use App\Game\Hand;

class Rules
{
    private array $hand;
    public function __construct($hand, $community)
    {
        $this->hand = [];
        $values = [
            "A" => "14",
            "K" => "13",
            "Q" => "12",
            "J" => "11",
            "10" => "10",
            "9" => "9",
            "8" => "8",
            "7" => "7",
            "6" => "6",
            "5" => "5",
            "4" => "4",
            "3" => "3",
            "2" => "2",
        ];
        
        foreach ($hand as $card) {
            array_push($this->hand, $card->getSuit() . $card->getValue());
        }
        foreach ($community as $card) {
            array_push($this->hand, $card->getSuit() . $card->getValue());
        }
    }

    public function getHand()
    {
        return $this->hand;
    }
}