<?php

namespace App\Poker;

use App\Game\Hand;

/**
 * Rules class to calculate how strong a hand is.
 * 
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Rules
{
    private array $hand;
    private array $scores;
    private array $values;
    public function __construct(array $player, array $community)
    {
        $this->hand = [];
        $this->values = [
            "A"     => "14",
            "K"     => "13",
            "Q"     => "12",
            "J"     => "11",
            "10"    => "10",
            "9"     => "9",
            "8"     => "8",
            "7"     => "7",
            "6"     => "6",
            "5"     => "5",
            "4"     => "4",
            "3"     => "3",
            "2"     => "2",
        ];

        foreach ($player as $card) {
            array_push($this->hand, $card->getSuit() . $this->values[$card->getFaceValue()]);
        }
        foreach ($community as $card) {
            array_push($this->hand, $card->getSuit() . $this->values[$card->getFaceValue()]);
        }

        $this->scores = [
            "royalFlush"     => $this->royalFlush(),
            "straightFlush"  => $this->straightFlush(),
            "fourOfAKind"    => $this->fourOfAKind(),
            "fullHouse"      => $this->fullHouse(),
            "flush"          => $this->flush(),
            "straight"       => $this->straight(),
            "threeOfAKind"   => $this->threeOfAKind(),
            "twoPair"        => $this->twoPair(),
            "pair"           => $this->pair(),
            "highCard"       => $this->highCard(),
        ];
    }

    /**
     * Returns the hand of cards
     * 
     * @return array The array of cards
     */
    public function getHand()
    {
        return $this->hand;
    }

    /**
     * Returns the suits of cards in hand
     * 
     * @return array The array of suits
     */
    public function getSuits()
    {
        $suits = [];
        foreach ($this->hand as $card) {
            array_push($suits, $card[0]);
        }
        return $suits;
    }

    /**
     * Returns the values of cards in hand
     * 
     * @return array The array of values
     */
    public function getValues()
    {
        $values = [];
        foreach ($this->hand as $card) {
            array_push($values, substr($card, 1));
        }
        return $values;
    }

    // public function getValuesIntegers()
    // {
    //     $values = [];
    //     foreach ($this->hand as $card) {
    //         array_push($values, $this->values[substr($card, 1)]);
    //     }
    //     return $values;
    // }

    /**
     * Returns the scoreboards
     * 
     * @return array An key value array of all the scores
     */
    public function getScore()
    {
        return $this->scores;
    }

    /**
     * Checks if royal flush is true
     * 
     * @return int 1 if true, 0 if false
     */
    public function royalFlush()
    {
        $straight = $this->straight();
        if ($straight == 14 && $this->straight()) {
            $this->scores["royalFlush"] = 1;
            return 1;
        }
        return 0;
    }

    /**
     * Checks if straight flush is true
     * 
     * @return int 1 if true, 0 if false
     */
    public function straightFlush()
    {
        $straight = $this->straight();
        if ($straight && $this->straight()) {
            $this->scores["straightFlush"] = 1;
            return 1;
        }
        return 0;
    }

    /**
     * Checks if four cards match is true
     * 
     * @return int value of matching card or 0
     */
    public function fourOfAKind()
    {
        $cards = $this->getValues();
        rsort($cards);
        for ($i = 0; $i < 4; $i++) {
            $count = 1;
            foreach (array_slice($cards, $i + 1) as $card) {
                if ($card == $cards[$i]) {
                    $count += 1;
                }
            }
            if ($count == 4) {
                $this->scores["fourOfAKind"] = intval($cards[$i]);
                return intval($cards[$i]);
            }
        }

        return 0;
    }

    /**
     * Checks if full house is true
     * 
     * @return int value of the set of 3 matching cards or 0
     */
    public function fullHouse()
    {
        $threeOfAKind = $this->threeOfAKind();
        if ($threeOfAKind && $this->twoPair()) {
            $this->scores["fullHouse"] = $threeOfAKind;
            return $threeOfAKind;
        }
        return 0;
    }

    /**
     * Checks if flush is true
     * 
     * @return int 1 if true, 0 if false
     */
    public function flush()
    {
        $cards = $this->getSuits();
        rsort($cards);
        for ($i = 0; $i < 3; $i++) {
            $count = 1;
            foreach (array_slice($cards, $i + 1) as $card) {
                if ($card == $cards[$i]) {
                    $count += 1;
                }
            }
            if ($count > 4) {
                $this->scores["flush"] = 1;
                return 1;
            }
        }
        return 0;
    }

    /**
     * Checks if a straight is true
     * 
     * @return int highest card in the straight or 0
     */
    public function straight()
    {
        $cards = array_unique($this->getValues());
        rsort($cards);
        $cardsLen = count($cards);
        for ($i = 0; $i < $cardsLen; $i++) {
            $count = 1;
            for ($j = 1; ($j + $i) < $cardsLen; $j++) {
                if ($cards[$i + $j] == $cards[$i] - $j) {
                    $count += 1;
                    if ($count == 5 || $count == 4 && $cards[$i] == "5" && in_array("14", $cards)) {
                        $this->scores["straight"] = intval($cards[$i]);
                        return intval($cards[$i]);
                    }
                }
            }
        }
        return 0;
    }

    /**
     * Checks if three cards match is true
     * 
     * @return int value of matching card or 0
     */
    public function threeOfAKind()
    {
        $cards = $this->getValues();
        rsort($cards);
        for ($i = 0; $i < 5; $i++) {
            $count = 1;
            foreach (array_slice($cards, $i + 1) as $card) {
                if ($card == $cards[$i]) {
                    $count += 1;
                }
            }
            if ($count > 2) {
                $this->scores["threeOfAKind"] = intval($cards[$i]);
                return intval($cards[$i]);
            }
        }

        return 0;
    }

    /**
     * Checks if two pair is true
     * 
     * @return int value of the highest matching cards or 0
     */
    public function twoPair()
    {
        $cards = $this->getValues();
        rsort($cards);
        $cardsLen = count($cards);
        $first = "";
        $counter = 0;
        for ($i = 0; $i < $cardsLen; $i++) {
            for ($j = $i + 1; $j < $cardsLen; $j++) {
                if ($cards[$i] == $cards[$j] && $cards[$i] != $first) {
                    $counter += 1;
                    if ($counter == 2) {
                        $this->scores["twoPair"] = intval($first);
                        return intval($first);
                    }
                    $first = $cards[$i];
                }
            }
        }
        return 0;
    }

    /**
     * Checks if pair is true
     * 
     * @return int value of matching card or 0
     */
    public function pair($cards = "")
    {
        if ($cards == "") {
            $cards = $this->getValues();
        }
        rsort($cards);
        for ($i = 0; $i < 6; $i++) {
            foreach (array_slice($cards, $i + 1) as $card) {
                if ($card == $cards[$i]) {
                    $this->scores["pair"] = intval($cards[$i]);
                    return intval($cards[$i]);
                }
            }
        }

        return 0;
    }

    /**
     * Returns all the cards in order high to low
     * 
     * @return array The array of sorted cards
     */
    public function highCard()
    {
        $cards = $this->getValues();
        rsort($cards);
        $this->scores["highCard"] = $cards;
        return $cards;
    }
}
