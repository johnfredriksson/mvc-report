<?php

namespace App\Card;

use App\Card\Deck;

/**
 * Class Deck2
 * 
 * A class that inherits from Deck, made to create a Deck with jokers added
 */
class Deck2 extends Deck
{
    /**
     * Adds two jokers to current deck
     */
    public function addJoker(): void
    {
        $newCards = ["BJ", "RJ"];
        foreach ($newCards as $card) {
            array_push($this->cards, new Card($card[0], $card[1]));
        }
    }
}
