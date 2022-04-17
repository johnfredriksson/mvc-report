<?php

namespace App\Game;

class Rules {
    public function blackjack($hand)
    {
        // if (count($hand) == 2 && $hand->getSum()[0] == 21) {
        //     return "blackjack";
        // }
        return (count($hand->getCards()) == 2 && $hand->getSum()[0] == 21);
    }
    
    public function fat($hand)
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