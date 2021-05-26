<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Round;
use App\Models\Yatzy\Points;
use App\Models\Yatzy\Histogram;

class Yatzy
{
    private string $mode;
    private string $bet;
    private string $opponent;
    private string $opponentName;
    private string $challengeId;
    private int $roundsCounter;
    private string $currentRound;
    private int $totalPoints;
    private object $currentHand;
    private object $points;
    private object $histogram;

    public function __construct(
        string $mode,
        string $bet,
        string $opponent,
        string $opponentName,
        string $challengeId
    ) {
        $this->mode = $mode;
        $this->bet = $bet;
        $this->opponent = $opponent;
        $this->opponentName = $opponentName;
        $this->challengeId = $challengeId;
        $this->roundsCounter = 0;
        $this->totalPoints = 0;
        $this->points = new Points();
        $this->histogram = new Histogram();
    }

    public function startNewRound(): array
    {
        $this->roundsCounter += 1;
        $this->currentHand = new Round();
        $this->totalPoints = $this->points->calcTotalPoints();
        $rollsAndValues = $this->currentHand->getRollsAndValues();
        $diceArray = array_slice($rollsAndValues, -5);
        $this->histogram->save($diceArray);

        $data = $this->createDataArray($rollsAndValues);

        return $data;
    }

    public function play($post): array
    {
        if (isset($post["roundOver"])) {
            $selectedRound = $post["selectedRound"];
            $roundsCounter = $this->roundsCounter;

            return $this->finishOneRound($selectedRound, $roundsCounter);
        }

        $diceToReroll = $this->extractSelectedDice($post);

        return $this->reRoll($diceToReroll);
    }

    private function extractSelectedDice($post): array
    {
        $diceToReroll = [];

        for ($i = 0; $i < 5; $i++) {
            if (isset($post[strval($i)])) {
                array_push($diceToReroll, $i);
            }
        }
        return $diceToReroll;
    }

    private function reRoll($diceToReroll): array
    {
        $rollsAndValues = $this->currentHand->rollDice($diceToReroll);
        $diceArray = array_slice($rollsAndValues, -5);

        $newDice = [];
        foreach ($diceToReroll as $index) {
            array_push($newDice, $diceArray[$index]);
        }
        $this->histogram->save($newDice);

        $data = $this->createDataArray($rollsAndValues);

        if ($data["nrOfRerolls"] >= 2) {
            $data["twoRerollsMade"] = "true";
        }
        return $data;
    }

    private function finishOneRound(string $selectedRound, int $roundsCounter): array
    {
        $this->currentRound = $selectedRound;

        $rollsAndValues = $this->currentHand->getRollsAndValues();
        $diceArray = array_slice($rollsAndValues, -5);
        $this->points->getRoundPoints($diceArray, $this->currentRound);

        if ($roundsCounter == 15) {
            $this->points->calcBonusPoints();
            $this->totalPoints = $this->points->calcTotalPoints();

            return $this->gameOver($rollsAndValues);
        }

        return $this->startNewRound();
    }

    private function gameOver($rollsAndValues): array
    {
        $data = $this->createDataArray($rollsAndValues);

        $data["twoRerollsMade"] = "true";
        $data["gameOver"] = "true";
        $data["frequency"] = $this->histogram->getDiceFrequency();
        $data["bonus"] = $this->points->calcBonusPoints();

        return $data;
    }

    private function createDataArray($rollsAndValues): array
    {
        $data = [];

        $data["nrOfRerolls"] = $rollsAndValues[0];
        $data["diceArray"] = array_slice($rollsAndValues, -5);
        $data["nrOfRoundsPlayed"] = $this->roundsCounter;
        $data["pointsPerRound"] = $this->points->getPointsArray();
        $data["bonus"] = -1;
        $data["totalPoints"] = $this->totalPoints;
        $data["mode"] = $this->mode;
        $data["bet"] = $this->bet;
        $data["opponent"] = $this->opponent;
        $data["opponentName"] = $this->opponentName;
        $data["challengeId"] = $this->challengeId;
        $data["twoRerollsMade"] = "false";
        $data["gameOver"] = "false";

        return $data;
    }
}
