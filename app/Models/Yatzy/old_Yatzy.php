<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Round;

class Yatzy
{
    private int $roundsCounter;
    private string $roundsLeft;
    private int $currentRound;
    private int $totalPoints;
    private object $currentHand;

    public function __construct()
    {
        $this->roundsCounter = 0;
        $this->totalPoints = 0;
        $this->roundsLeft = "123456";
    }

    public function startNewRound(): array
    {
        $data = [];

        $this->roundsCounter += 1;
        $this->currentHand = new Round();
        $rollsAndValues = $this->currentHand->getRollsAndValues();

        $data["nrOfRerolls"] = $rollsAndValues[0];
        $data["diceArray"] = array_slice($rollsAndValues, -5);
        $data["nrOfRoundsPlayed"] = $this->roundsCounter;
        $roundsLeft = str_split($this->roundsLeft);
        $data["roundsLeft"] = $roundsLeft;
        $data["totalPoints"] = $this->totalPoints;
        $data["hideOn2RerollsMade"] = "";
        $data["showOn2RerollsMade"] = "hidden";
        $data["hideOnGameOver"] = "";
        $data["showOnGameOver"] = "hidden";

        return $data;
    }

    public function play($post): array
    {
        $data = [];

        if (isset($post["roundOver"])) {
            $this->roundsLeft = str_replace($post["selectedRound"], "", $this->roundsLeft);
            $this->currentRound = intval($post["selectedRound"]);

            $rollsAndValues = $this->currentHand->getRollsAndValues();
            $data["diceArray"] = array_slice($rollsAndValues, -5);

            $this->calculatePoints($data["diceArray"], $this->currentRound, $this->roundsCounter);
            $data["totalPoints"] = $this->totalPoints;

            return $this->startNewRound();
        }
        $diceToReroll = [];
        for ($i = 0; $i < 5; $i++) {
            if (isset($post[strval($i)])) {
                array_push($diceToReroll, $i);
            }
        }
        return $this->reRoll($diceToReroll);
    }

    public function reRoll($diceToReroll): array
    {
        $data = [];

        $rollsAndValues = $this->currentHand->rollDice($diceToReroll);

        $data["nrOfRerolls"] = $rollsAndValues[0];
        $data["diceArray"] = array_slice($rollsAndValues, -5);
        $data["nrOfRoundsPlayed"] = $this->roundsCounter;
        $roundsLeft = str_split($this->roundsLeft);
        $data["roundsLeft"] = $roundsLeft;
        $data["totalPoints"] = $this->totalPoints;
        $data["hideOn2RerollsMade"] = "";
        $data["showOn2RerollsMade"] = "hidden";
        $data["hideOnGameOver"] = "";
        $data["showOnGameOver"] = "hidden";

        if ($data["nrOfRerolls"] >= 2) {
            $data["hideOn2RerollsMade"] = "hidden";
            $data["showOn2RerollsMade"] = "";
            if ($this->roundsCounter == 6) {
                $this->currentRound = intval($this->roundsLeft);

                $this->calculatePoints($data["diceArray"], $this->currentRound, $this->roundsCounter);
                $data["totalPoints"] = $this->totalPoints;

                $this->roundsLeft = "";
                $data["roundsLeft"] = [""];
                $data["hideOnGameOver"] = "hidden";
                $data["showOnGameOver"] = "";
            }
        }
        return $data;
    }

    private function calculatePoints($diceArray, $chosenRound, $roundsPlayed): int
    {
        foreach ($diceArray as $value) {
            if ($value == $chosenRound) {
                $this->totalPoints += $value;
            }
        }
        if (($roundsPlayed == 6) && ($this->totalPoints >= 63)) {
            $this->totalPoints += 50;
        }
        return $this->totalPoints;
    }
}
