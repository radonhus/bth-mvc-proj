<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Round;
use App\Models\Yatzy\Points;
use App\Models\Yatzy\Histogram;

class Yatzy
{
    private int $roundsCounter;
    private string $currentRound;
    private int $totalPoints;
    private object $currentHand;
    private object $points;
    private object $histogram;

    public function __construct()
    {
        $this->roundsCounter = 0;
        $this->totalPoints = 0;
        $this->points = new Points();
        $this->histogram = new Histogram();
    }

    public function startNewRound(): array
    {
        $data = [];

        $this->roundsCounter += 1;
        $this->currentHand = new Round();
        $this->totalPoints = $this->points->calcTotalPoints();
        $rollsAndValues = $this->currentHand->getRollsAndValues();
        $diceArray = array_slice($rollsAndValues, -5);

        $this->histogram->save($diceArray);

        $data["frequency"] = $this->histogram->getDiceFrequency();

        $data["nrOfRerolls"] = $rollsAndValues[0];
        $data["diceArray"] = $diceArray;
        $data["nrOfRoundsPlayed"] = $this->roundsCounter;

        $data["pointsPerRound"] = $this->points->getPointsArray();
        $data["bonus"] = -1;
        $data["totalPoints"] = $this->totalPoints;

        $data["hideOn2RerollsMade"] = "";
        $data["showOn2RerollsMade"] = "hidden";
        $data["hideOnGameOver"] = "";
        $data["showOnGameOver"] = "hidden";

        return $data;
    }

    public function play($post): array
    {
        if (isset($post["roundOver"])) {
            $this->currentRound = $post["selectedRound"];

            $rollsAndValues = $this->currentHand->getRollsAndValues();
            $diceArray = array_slice($rollsAndValues, -5);
            $this->points->getRoundPoints($diceArray, $this->currentRound);

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
        $diceArray = array_slice($rollsAndValues, -5);

        $newDice = [];
        foreach ($diceToReroll as $index) {
            array_push($newDice, $diceArray[$index]);
        }
        $this->histogram->save($newDice);

        $data["frequency"] = $this->histogram->getDiceFrequency();

        $data["nrOfRerolls"] = $rollsAndValues[0];
        $data["diceArray"] = $diceArray;

        $data["nrOfRoundsPlayed"] = $this->roundsCounter;
        $data["pointsPerRound"] = $this->points->getPointsArray();
        $data["bonus"] = -1;
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
        $data["bonus"] = $this->points->calcBonusPoints();
        $data["totalPoints"] = $this->totalPoints;
        $data["frequency"] = $this->histogram->getDiceFrequency();

        $data["hideOn2RerollsMade"] = "hidden";
        $data["showOn2RerollsMade"] = "";

        $data["hideOnGameOver"] = "hidden";
        $data["showOnGameOver"] = "";

        return $data;
    }

}
