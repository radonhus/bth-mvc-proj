<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use ReflectionMethod;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Yatzy class of the Yatzy game.
 */
class YatzyYatzyTest extends TestCase
{

    /**
     * Test if object is instance of Yatzy
     */
    public function testIsInstanceOfRound()
    {
        $yatzyObject = new Yatzy();

        $this->assertInstanceOf("App\Models\Yatzy\Yatzy", $yatzyObject);
    }

    /**
     * Test that startNewRound increments nrOfRoundsPlayed in data array
     */
    public function testStartNewRoundNrOfRoundsPlayed()
    {
        $yatzyObject = new Yatzy();
        $dataFirstRound = $yatzyObject->startNewRound();
        $dataSecondRound = $yatzyObject->startNewRound();
        $dataThirdRound = $yatzyObject->startNewRound();

        $this->assertEquals($dataFirstRound["nrOfRoundsPlayed"], 1);
        $this->assertEquals($dataSecondRound["nrOfRoundsPlayed"], 2);
        $this->assertEquals($dataThirdRound["nrOfRoundsPlayed"], 3);
    }

    /**
     * Test that play increments nrOfReRolls in data array
     */
    public function testPlayNrOfReRolls()
    {
        $yatzyObject = new Yatzy();
        $dataInitialRoll = $yatzyObject->startNewRound();
        $post = [
            "roll" => "Roll selected dice",
            2 => "selected"
        ];
        $dataAfterFirstRoll = $yatzyObject->play($post);

        $this->assertEquals($dataInitialRoll["nrOfRerolls"], 0);
        $this->assertEquals($dataAfterFirstRoll["nrOfRerolls"], 1);
    }

    /**
     * Test that play increments nrOfRoundsPlayed if "roundOver" is set
     */
    public function testPlayRoundOver()
    {
        $yatzyObject = new Yatzy();
        $dataInitialRoll = $yatzyObject->startNewRound();
        $post = [
            "roundOver" => "Save points + start next round",
            "selectedRound" => "4"
        ];
        $dataAfterFirstRoll = $yatzyObject->play($post);

        $this->assertEquals($dataInitialRoll["nrOfRoundsPlayed"], 1);
        $this->assertEquals($dataAfterFirstRoll["nrOfRoundsPlayed"], 2);
    }

    /**
     * Test that 2 rerolls sets hideOn2RerollsMade in data array to "hidden"
     */
    public function testReRollHidden()
    {
        $publicReRoll = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'reRoll');
        $publicReRoll->setAccessible(true);

        $yatzyObject = new Yatzy();
        $dataInitialRoll = $yatzyObject->startNewRound();

        $dataAfterFirstRoll = $publicReRoll->invokeArgs($yatzyObject, [[]]);
        $dataAfterSecondRoll = $publicReRoll->invokeArgs($yatzyObject, [[]]);

        $this->assertEquals($dataInitialRoll["hideOn2RerollsMade"], "");
        $this->assertEquals($dataAfterFirstRoll["hideOn2RerollsMade"], "");
        $this->assertEquals($dataAfterSecondRoll["hideOn2RerollsMade"], "hidden");
    }

    /**
     * Test that 6 played rounds and 2 rerolls sets hideOnGameOver to hidden
     */
    public function testReRollHideOnGameOver()
    {
        $publicReRoll = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'reRoll');
        $publicReRoll->setAccessible(true);

        $yatzyObject = new Yatzy();
        $dataInitialRoll = $yatzyObject->startNewRound();

        $yatzyObject->startNewRound();
        $yatzyObject->startNewRound();
        $yatzyObject->startNewRound();
        $yatzyObject->startNewRound();
        $yatzyObject->startNewRound();

        $publicReRoll->invokeArgs($yatzyObject, [[]]);

        $dataAfterSixRounds = $publicReRoll->invokeArgs($yatzyObject, [[]]);

        $this->assertEquals($dataInitialRoll["hideOnGameOver"], "");
        $this->assertEquals($dataAfterSixRounds["hideOnGameOver"], "hidden");
    }

    /**
     * Test that expected points is calculated
     */
    public function testCalculatePoints()
    {
        $publicCalc = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'calculatePoints');
        $publicCalc->setAccessible(true);

        $yatzyObject = new Yatzy();
        $yatzyObject->startNewRound();

        $pointsFirstRound = $publicCalc->invokeArgs($yatzyObject, [[5, 5, 5, 5, 5], 5, 4]);
        $pointsSecondRound = $publicCalc->invokeArgs($yatzyObject, [[6, 6, 6, 6, 6], 6, 5]);

        $this->assertEquals($pointsFirstRound, 25);
        $this->assertEquals($pointsSecondRound, 55);
    }

    /**
     * Test that 50 bonus points are awarded
     */
    public function testCalculatePointsBonus()
    {
        $publicCalc = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'calculatePoints');
        $publicCalc->setAccessible(true);

        $yatzyObject = new Yatzy();
        $yatzyObject->startNewRound();

        $pointsFirstRound = $publicCalc->invokeArgs($yatzyObject, [[100], 100, 6]);

        $this->assertEquals($pointsFirstRound, 150);
    }
}
