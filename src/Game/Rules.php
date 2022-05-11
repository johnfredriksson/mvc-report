<?php

namespace App\Game;

/**
 * Class Rules
 *
 * Holds basic rules in blackjack
 */
class Rules
{
    /**
     * Checks if hand has a blackjack, requirements are
     * hand needs to have just two cards and sum of 21.
     *
     * Returns boolean
     */
    public function blackjack(Hand $hand): bool
    {
        return (count($hand->getCards()) == 2 && $hand->getSum()[0] == 21);
    }

    /**
     * Checks if hand is fat, exceds 21.
     *
     * Returns boolean
     */
    public function fat(Hand $hand): bool
    {
        $check = true;
        foreach ($hand->getSum() as $sum) {
            if ($sum < 22) {
                $check = false;
            }
        }
        return $check;
    }
}
