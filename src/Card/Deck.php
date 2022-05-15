<?php

namespace App\Card;

use App\Card\Card;

/**
 * Class Deck
 *
 * Holds a deck of cards with functions to draw cards from the deck and shuffle it
 */
class Deck
{
    protected array $suits = ["C", "D", "H", "S"];
    protected array $values = ["A","2","3","4","5","6","7","8","9","10","J","Q","K"];
    protected array $cards = [];

    /**
     * Constructor
     *
     * Uses arrays of its attributes to generate a new card of each combination with suit and value.
     * Sets result as attribute cards.
     */
    public function __construct()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->values as $value) {
                array_push($this->cards, new Card($suit, $value));
            }
        }
    }

    /**
     * Returns current deck
     */
    public function getDeck(): array
    {
        return $this->cards;
    }

    /**
     * Draws from the first cards in the deck, removing the card(s) from the deck and
     * returns said card(s) image url.
     * Incoming parameter amount decides how many cards will be drawn
     */
    public function drawCard(int $amount): array
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

    /**
     * Draws from the first cards in the deck, removing the card(s) from the deck and
     * returns said card(s) object(s).
     * Incoming parameter amount decides how many cards will be drawn
     */
    public function drawCardFull(int $amount): array
    {
        $res = [];
        for ($i = 0; $i < $amount; $i++) {
            $size = count($this->cards);
            if ($size > 0) {
                $card = $this->cards[0];
                array_splice($this->cards, 0, 1);
                array_push($res, $card);
            }
        }
        return $res;
    }

    /**
     * Shuffles the current deck
     */
    public function shuffleDeck()
    {
        shuffle($this->cards);
    }

    /**
     * counts how many cards remains in the deck, returns result
     */
    public function countDeck(): int
    {
        return count($this->cards);
    }

    /**
     * Returns a JSON formated string of current deck
     */
    public function getJson(): string
    {
        $res = [];
        foreach ($this->cards as $card) {
            array_push($res, $card->getObject());
        }
        return json_encode($res, JSON_PRETTY_PRINT);
    }
}
