<?php

namespace App\Game;

class Rules
{
    public function blackjack(Hand $hand): bool
    {
        return (count($hand->getCards()) == 2 && $hand->getSum()[0] == 21);
    }

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
