<?php

namespace App\Poker;

/**
 * Class to handle banks decision making
 */
class BankLogic
{
    /**
     * Rolls a random number between 0, 100.
     *
     * @return integer
     */
    public function getOdds()
    {
        return rand(1, 100);
    }

    /**
     * Makes choice based upon bet, returns decision.
     *
     * @param integer $number An integer to represent the number
     * @return string
     *
     * @return string
     */
    public function bet($number = null)
    {
        if (!$number) {
            $number = $this->getOdds();
        }

        if ($number < 6) {
            return "fold";
        }

        if ($number > 70) {
            return "raise";
        }

        return "check";
    }

    /**
     * In case of a raise, method will adjust the
     * amount in regard to players balance and the blind.
     *
     * @param integer $blind            The current blind amount
     * @param integer $playerBalance    The players current balance
     *
     * @return integer $amount          The calculated amount to raise
     */
    public function raise($blind, $playerBalance)
    {
        if ($this->getOdds() <= 2 || $playerBalance <= $blind) {
            return $playerBalance;
        }

        $amount = rand($blind, $blind * 3);

        if ($amount >= $playerBalance) {
            return $playerBalance;
        }

        return $amount;
    }
}
