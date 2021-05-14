<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Round;
use App\Models\Yatzy\Points;

class Yatzy
{
    private int $roundsCounter;
    private string $currentRound;
    private int $totalPoints;
    private object $currentHand;
    private object $points;

    public function __construct()
    {
        $this->roundsCounter = 0;
        $this->totalPoints = 0;
        $this->points = new Points();
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

        $data["pointsPerRound"] = $this->points->getPointsArray();
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
            $this->currentRound = $post["selectedRound"];

            $rollsAndValues = $this->currentHand->getRollsAndValues();
            $data["diceArray"] = array_slice($rollsAndValues, -5);

            $this->points->getRoundPoints($data["diceArray"], $this->currentRound);
            $this->totalPoints = $this->points->calcTotalPoints();
            $data["totalPoints"] = $this->totalPoints;

            if ($this->roundsCounter == 15) {

                $this->points->calcBonusPoints();
                $this->totalPoints = $this->points->calcTotalPoints();

                return $this->gameOver($rollsAndValues);
            }

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
        $data["pointsPerRound"] = $this->points->getPointsArray();
        $data["totalPoints"] = $this->totalPoints;

        $data["hideOn2RerollsMade"] = "";
        $data["showOn2RerollsMade"] = "hidden";
        $data["hideOnGameOver"] = "";
        $data["showOnGameOver"] = "hidden";

        if ($data["nrOfRerolls"] >= 2) {
            $data["hideOn2RerollsMade"] = "hidden";
            $data["showOn2RerollsMade"] = "";
        }
        return $data;
    }

    private function gameOver($rollsAndValues): array
    {
        $data = [];

        $data["nrOfRerolls"] = $rollsAndValues[0];
        $data["diceArray"] = array_slice($rollsAndValues, -5);
        $data["nrOfRoundsPlayed"] = $this->roundsCounter;
        $data["pointsPerRound"] = $this->points->getPointsArray();
        $data["totalPoints"] = $this->totalPoints;

        $data["hideOn2RerollsMade"] = "hidden";
        $data["showOn2RerollsMade"] = "";

        $data["hideOnGameOver"] = "hidden";
        $data["showOnGameOver"] = "";

        return $data;
    }

}
