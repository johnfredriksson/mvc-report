<?php

namespace App\Card;

class Card
{
    protected string $suit;
    protected string $value;
    protected string $img;
    protected string $title;

    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->value = $value;
        $this->img = $suit . $value;
        if ($this->value == "A") {
            $value = "Ace";
        }
        if ($suit == "C") {
            $this->title = $value . " of Clubs";
        }
        if ($suit == "D") {
            $this->title = $value . " of Diamonds";
        }
        if ($suit == "H") {
            $this->title = $value . " of Hearts";
        }
        if ($suit == "S") {
            $this->title = $value . " of Spades";
        }
    }

    public function getImgUrl(): string
    {
        return $this->img . ".png";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getObject(): array
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
