<?php

namespace App\Card;

use App\Card\Card;

class Deck
{
    protected $suits = ["C", "D", "H", "S"];
    protected $values = ["A","2","3","4","5","6","7","8","9","10","J","Q","K"];
    protected $cards = [];

    public function __construct()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                array_push($this->cards, new Card($suit, $value));
            }
        }
    }

    public function getDeck()
    {
        return $this->cards;
    }

    public function drawCard($amount)
    {
        $res = [];
        for ($i = 0; $i < $amount; $i++) {
            $size = count($this->cards);
            if ($size > 0) {
                $card = $this->cards[0];
                array_splice($this->cards, 0, 1);
                array_push($res, $card->getImgUrl());
            }
        }
        return $res;
    }

    public function shuffleDeck()
    {
        shuffle($this->cards);
    }

    public function countDeck()
    {
        return count($this->cards);
    }

    public function getJson()
    {
        $res = [];
        foreach ($this->cards as $card) {
            array_push($res, $card->getObject());
        }
        return json_encode($res, JSON_PRETTY_PRINT);
    }
}