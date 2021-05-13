<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Hand;

class Round
{
    private object $hand;
    private int $reRollsCounter;

    public function __construct()
    {
        $this->hand = new Hand();
        $this->reRollsCounter = 0;
    }

    public function getRollsAndValues(): array
    {
        $values = $this->hand->getDiceValues();
        array_unshift($values, $this->reRollsCounter);
        return $values;
    }

    public function rollDice($keys): array
    {
        $this->hand->rollSelectedDice($keys);
        $this->reRollsCounter += 1;

        return $this->getRollsAndValues();
    }
}
