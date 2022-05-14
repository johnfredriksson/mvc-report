<?php

namespace App\Poker;

class BankLogic
{
    public function getOdds()
    {
        return rand(1,100);
    }
    public function bet()
    {
        $number = $this->getOdds();

        if ($number < 6) {
            return "fold";
        }

        if ($number > 70) {
            return "raise";
        }

        return "check";
    }

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