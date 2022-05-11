<?php

namespace App\Game;

/**
 * Hand class
 *
 * Holds a set of cards, connected to a player
 */
class Hand
{
    protected array $cards;

    /**
     * Constructor
     *
     * Recieves an array of cards as a parameter, constructor will
     * place these in attribute cards
     */
    public function __construct(array $cards)
    {
        $this->cards = $cards;
    }

    /**
     * Recieves a card as a parameter, adds this to attribute cards
     */
    public function addCard(array $card)
    {
        array_push($this->cards, $card[0]);
    }

    /**
     * Returns cards in the hand
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * Returns sum of cards in hand.
     *
     * Ace can count as 1 or 11, therefore we create
     * two arrays, one will count Ace as 1 and the
     * other counts it as 11.
     *
     * If the largest array exceds 21 it will not be returned
     */
    public function getSum(): array
    {
        $sum = 0;
        $sum2 = 0;
        $check = false;
        foreach ($this->cards as $card) {
            if ($card->getValue() == "A") {
                $sum += 1;
                $sum2 += 11;
                $check = true;
                continue;
            }
            $sum += intval($card->getValue());
            $sum2 += intval($card->getValue());
        }
        if ($sum == 2 && $sum2 == 22) {
            return [12, 2];
        }
        if ($check && $sum2 < 22) {
            $res = [$sum, $sum2];
            rsort($res);
            return $res;
        }
        return [$sum];
    }
}
