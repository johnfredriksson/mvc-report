<?php

namespace App\Game;

class Hand {
    protected $cards;

    public function __construct($cards)
    {
        $this->cards = $cards;
    }

    public function addCard($card)
    {
        array_push($this->cards, $card[0]);
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function getSum() {
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
            if (str_contains("KQJ", $card->getValue())) {
                $sum += 10;
                $sum2 += 10;
                continue;
            }
            $sum += intval($card->getValue());
            $sum2 += intval($card->getValue());
        }
        if ($check && $sum2 < 22) {
            $res = [$sum, $sum2];
            rsort($res);
            return $res;
        }
        return [$sum];
    }
}