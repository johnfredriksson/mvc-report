<?php

namespace App\Card;

/**
 * Class Card
 * 
 * Creates a card based on incoming parameters. It sets 
 * the value, suit and generates a image url and title
 */
class Card
{
    protected string $suit;
    protected string $value;
    protected string $img;
    protected string $title;

    /**
     * Constructor
     * 
     * Takes incoming parameters of suit and value and applies 
     * it to a new card. Also generates a image url and a card title.
     */
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

    /**
     * Returns the cards image url
     */
    public function getImgUrl(): string
    {
        return $this->img . ".png";
    }

    /**
     * Returns the cards value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the whole card object
     */
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
