<?php

namespace App\Card;

use App\Card\Deck;

class Deck2 extends Deck
{
    public function addJoker(): void
    {
        $newCards = ["BJ", "RJ"];
        foreach ($newCards as $card) {
            array_push($this->cards, new Card($card[0], $card[1]));
        }
    }
}
