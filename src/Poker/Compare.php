<?php

namespace App\Poker;

use App\Poker\Rules;

/**
 * Class Compare
 *
 * Compares to hands in texas hold'em
 */
class Compare
{
    private array $player;
    private array $bank;
    private array $rules;
    private array $prettyRules;
    /**
     * Constructor
     *
     * Calculates
     *
     * @param array $player Players scoreboard
     * @param array $bank Banks scoreboard
     */
    public function __construct($player, $bank)
    {
        $this->player = $player;
        $this->bank   = $bank;
        $this->rules  = [
            "royalFlush",
            "straightFlush",
            "fourOfAKind",
            "fullHouse",
            "flush",
            "straight",
            "threeOfAKind",
            "twoPair",
            "pair",
            "highCard",
        ];
        $this->prettyRules = [
            "royalFlush"     => "Royal Flush",
            "straightFlush"  => "Straight Flush",
            "fourOfAKind"    => "Four Of A Kind",
            "fullHouse"      => "Full House",
            "flush"          => "Flush",
            "straight"       => "Straight",
            "threeOfAKind"   => "Three Of A Kind",
            "twoPair"        => "Two Pair",
            "pair"           => "Pair",
            "highCard"       => "High Card",
        ];
    }

    /**
     * Checks from the highest earning scores to the lowest to find a difference in score for the current rule.
     * If one is higher, that hand wins.
     *
     * If all rules are the same winner will be decided by highest card.
     * Two identical hands returns a draw
     *
     * @return array Array of who won, and by what rule
     */
    public function compareHands()
    {
        $player = $this->player;
        $bank   = $this->bank;
        $rules  = $this->rules;

        foreach ($rules as $rule) {
            if ($player[$rule] > $bank[$rule]) {
                return ["You", $this->prettyRules[$rule]];
            }
            if ($player[$rule] < $bank[$rule]) {
                return ["Bank", $this->prettyRules[$rule]];
            }
            if ($player[$rule] > 0) {
                if ($player["highCard"] > $bank["highCard"]) {
                    return ["You", "High Card"];
                }
                if ($player["highCard"] < $bank["highCard"]) {
                    return ["Bank", "High Card"];
                }
            }
        }
        return ["No One", "Draw"];
    }
}
