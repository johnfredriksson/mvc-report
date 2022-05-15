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
    protected string $faceValue;

    /**
     * Constructor
     *
     * Takes incoming parameters of suit and value and applies
     * it to a new card. Also generates a image url and a card title.
     */
    public function __construct(string $suit, string $value)
    {
        $this->suit = $suit;
        $this->img = $suit . $value;
        $this->faceValue = $value;
        $suits = [
            "C" => "Clubs",
            "D" => "Diamonds",
            "H" => "Hearts",
            "S" => "Spades",
            "B" => "Black",
            "R" => "Red"
        ];
        $this->title = $value . " of " . $suits[$suit];
        if (str_contains("KQJ", $value)) {
            $this->value = "10";
            return;
        }
        $this->value = $value;
        return;
    }

    /**
     * Returns the cards image url
     */
    public function getImgUrl(): string
    {
        return $this->img . ".png";
    }

    /**
     * Returns suit + value as string
     */
    public function getSuitValue(): string
    {
        return $this->suit . $this->faceValue;
    }

    /**
     * Returns value as string
     */
    public function getFaceValue(): string
    {
        return $this->faceValue;
    }

    /**
     * Returns the cards value
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the cards value
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Returns the whole card
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
