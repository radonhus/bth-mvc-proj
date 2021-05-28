<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

class Points
{
    private array $pointsArray;
    private int $bonus;

    public function __construct()
    {
        $this->bonus = 0;
        $this->pointsArray = [
            "1" => -1, "2" => -1, "3" => -1, "4" => -1, "5" => -1, "6" => -1,
            "one_pair" => -1, "two_pairs" => -1, "three" => -1,
            "four" => -1, "small_straight" => -1, "large_straight" => -1,
            "full_house" => -1, "chance" => -1, "yatzy" => -1
        ];
    }

    public function getPointsArray(): array
    {
        return $this->pointsArray;
    }

    public function calcTotalPoints(): int
    {
        $total = 0;
        foreach ($this->pointsArray as $points) {
            if ($points >= 0) {
                $total += $points;
            }
        }

        $total += $this->bonus;

        return $total;
    }

    public function calcBonusPoints(): int
    {
        $firstSix = 0;
        for ($i = 1; $i < 7; $i++) {
            $firstSix += $this->pointsArray[strval($i)];
        }

        if ($firstSix > 62) {
            $this->bonus = 50;
        }

        return $this->bonus;
    }

    public function getRoundPoints($diceArray, $chosenRound): int
    {
        rsort($diceArray);

        $pointsThisRound = 0;

        if (in_array($chosenRound, ["1","2","3","4","5","6"])) {
            $pointsThisRound = $this->calc16($diceArray, intval($chosenRound));
            $this->pointsArray[$chosenRound] = $pointsThisRound;
            return $pointsThisRound;
        }

        switch ($chosenRound) {
            case "one_pair":
                $pointsThisRound = $this->calcOnePair($diceArray);
                break;
            case "two_pairs":
                $pointsThisRound = $this->calcTwoPairs($diceArray);
                break;
            case "three":
                $pointsThisRound = $this->calcThree($diceArray);
                break;
            case "four":
                $pointsThisRound = $this->calcFour($diceArray);
                break;
            case "small_straight":
                $pointsThisRound = $this->calcSmallStraight($diceArray);
                break;
            case "large_straight":
                $pointsThisRound = $this->calcLargeStraight($diceArray);
                break;
            case "full_house":
                $pointsThisRound = $this->calcFullHouse($diceArray);
                break;
            case "chance":
                $pointsThisRound = $this->calcChance($diceArray);
                break;
            case "yatzy":
                $pointsThisRound = $this->calcYatzy($diceArray);
                break;
        }

        $this->pointsArray[$chosenRound] = $pointsThisRound;

        return $pointsThisRound;
    }

    private function calcYatzy($diceArray): int
    {
        if (count(array_count_values($diceArray)) == 1) {
            return 50;
        }
        return 0;
    }

    private function calcChance($diceArray): int
    {
        return array_sum($diceArray);
    }

    private function calcFullHouse($diceArray): int
    {

        $freq = array_count_values($diceArray);
        arsort($freq);

        $highestFreq = reset($freq);
        $secondHighestFreq = next($freq);
        reset($freq);

        if (($highestFreq >= 3) && ($secondHighestFreq >= 2)) {
            return array_sum($diceArray);
        }
        return 0;
    }

    private function calcLargeStraight($diceArray): int
    {
        $correctValue = 6;
        for ($i = 0; $i < 5; $i++) {
            if ($diceArray[$i] != $correctValue) {
                return 0;
            }
            $correctValue -= 1;
        }
        return 20;
    }

    private function calcSmallStraight($diceArray): int
    {
        $correctValue = 5;
        for ($i = 0; $i < 5; $i++) {
            if ($diceArray[$i] != $correctValue) {
                return 0;
            }
            $correctValue -= 1;
        }
        return 15;
    }

    private function calcFour($diceArray): int
    {
        $freq = array_count_values($diceArray);
        arsort($freq);

        $highestFreq = reset($freq);

        if ($highestFreq >= 4) {
            return key($freq) * 4;
        }
        return 0;
    }

    private function calcThree($diceArray): int
    {
        $freq = array_count_values($diceArray);
        arsort($freq);

        $highestFreq = reset($freq);

        if ($highestFreq >= 3) {
            return key($freq) * 3;
        }
        return 0;
    }

    private function calcTwoPairs($diceArray): int
    {
        $freq = array_count_values($diceArray);
        arsort($freq);

        $highestFreq = reset($freq);
        $secondHighestFreq = next($freq);
        reset($freq);

        if (($highestFreq >= 2) && ($secondHighestFreq >= 2)) {
            $result = key($freq) * 2;
            next($freq);
            $result += key($freq) * 2;
            return $result;
        }
        return 0;
    }

    private function calcOnePair($diceArray): int
    {
        $points = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($diceArray[$i] == $diceArray[$i + 1]) {
                $points = $diceArray[$i] * 2;
                break;
            }
        }
        return $points;
    }

    private function calc16($diceArray, $chosenRound): int
    {
        $freq = array_count_values($diceArray);

        if (array_key_exists($chosenRound, $freq)) {
            return $freq[$chosenRound] * $chosenRound;
        }
        return 0;
    }
}
