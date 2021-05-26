<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

class Histogram
{
    private array $histogramArray;
    // private array $diceFrequency;

    public function __construct()
    {
        $this->histogramArray = [];
        // $this->diceFrequency = [];
    }

    public function save(array $diceValues)
    {
        foreach ($diceValues as $value) {
            array_push($this->histogramArray, $value);
        }

        return $this->histogramArray;
    }

    public function getDiceFrequency(): array
    {
        return array_count_values($this->histogramArray);
    }
}
