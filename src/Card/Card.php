<?php

namespace App\Card;

class Card
{
    protected $suit;
    protected $value;
    protected $img;
    protected $title;

    public function __construct($suit, $value)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->img = $suit . $value;
        if ($this->value == "A") {
            $value = "Ace";
        }
        if ($suit == "C") {
            $this->title = $value . " of Clubs";
        } elseif ($suit == "D") {
            $this->title = $value . " of Diamonds";
        } elseif ($suit == "H") {
            $this->title = $value . " of Hearts";
        } elseif ($suit == "S") {
            $this->title = $value . " of Spades";
        } else {
            $this->title = $suit . " " . $value;
        }
    }

    public function getImgUrl()
    {
        return $this->img . ".png";
    }

    public function getObject()
    {
        $res = [
            "suit" => $this->suit,
            "value" => $this->value,
            "img" => $this->img,
            "title" => $this->title
        ];
        return $res;
    }
}
