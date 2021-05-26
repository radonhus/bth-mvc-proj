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
        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");

        $this->assertInstanceOf("App\Models\Yatzy\Yatzy", $yatzyObject);
    }

    /**
     * Test that startNewRound increments nrOfRoundsPlayed in data array
     */
    public function testStartNewRoundNrOfRoundsPlayed()
    {
        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");
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
        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");
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
        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");
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
     * Test that extractSelectedDice returns the expected array
     */
    public function testExtractSelectedDice()
    {
        $publicExtract = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'extractSelectedDice');
        $publicExtract->setAccessible(true);

        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");

        $diceArray1 = $publicExtract->invokeArgs($yatzyObject, [["3" => true, "2" => true]]);
        $diceArray2 = $publicExtract->invokeArgs($yatzyObject, [["4" => true, "1" => true]]);
        $diceArray3 = $publicExtract->invokeArgs($yatzyObject, [["1" => true, "0" => true]]);

        $this->assertEquals([2, 3], $diceArray1);
        $this->assertEquals([1, 4], $diceArray2);
        $this->assertEquals([0, 1], $diceArray3);
    }

    /**
     * Test that two rerolls sets twoRerollsMade in data array to "hidden"
     */
    public function testTwoReRollsMadeTrue()
    {
        $publicReRoll = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'reRoll');
        $publicReRoll->setAccessible(true);

        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");
        $dataInitialRoll = $yatzyObject->startNewRound();

        $dataAfterFirstRoll = $publicReRoll->invokeArgs($yatzyObject, [[]]);
        $dataAfterSecondRoll = $publicReRoll->invokeArgs($yatzyObject, [[]]);

        $this->assertEquals($dataInitialRoll["twoRerollsMade"], "false");
        $this->assertEquals($dataAfterFirstRoll["twoRerollsMade"], "false");
        $this->assertEquals($dataAfterSecondRoll["twoRerollsMade"], "true");
    }

    /**
     * Test that FinishOneRound increases nrOfRoundsPlayed by 1
     */
    public function testFinishOneRound()
    {
        $publicFinish = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'finishOneRound');
        $publicFinish->setAccessible(true);

        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");
        $dataInitialRound = $yatzyObject->startNewRound();

        $dataSecondRound = $publicFinish->invokeArgs($yatzyObject, ["full_house", 1]);

        $this->assertEquals("1", $dataInitialRound["nrOfRoundsPlayed"]);
        $this->assertEquals("2", $dataSecondRound["nrOfRoundsPlayed"]);
        $this->assertEquals("false", $dataSecondRound["gameOver"]);
    }

    /**
     * Test that calling FinishOneRound after 15 rounds results in gameOver == true
     */
    public function testFinishLastRound()
    {
        $publicFinish = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'finishOneRound');
        $publicFinish->setAccessible(true);

        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");
        $dataInitialRound = $yatzyObject->startNewRound();

        $dataSecondRound = $publicFinish->invokeArgs($yatzyObject, ["full_house", 15]);

        $this->assertEquals("false", $dataInitialRound["gameOver"]);
        $this->assertEquals("true", $dataSecondRound["gameOver"]);
    }

    /**
     * Test that CreateDataArray returns expected values
     */
    public function testGameOverReturnsCorrectData()
    {
        $publicGameOver = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'gameOver');
        $publicGameOver->setAccessible(true);

        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");

        $data = $publicGameOver->invokeArgs($yatzyObject, [[5, 1, 1, 1, 1, 1]]);

        $this->assertEquals("true", $data["gameOver"]);
        $this->assertEquals([], $data["frequency"]);
    }

    /**
     * Test that CreateDataArray returns expected values
     */
    public function testCreateDataArrayReturnsCorrectData()
    {
        $publicCreateData = new ReflectionMethod('App\Models\Yatzy\Yatzy', 'createDataArray');
        $publicCreateData->setAccessible(true);

        $yatzyObject = new Yatzy("challenge", "100", "1", "", "1");

        $data = $publicCreateData->invokeArgs($yatzyObject, [[5, 1, 1, 1, 1, 1]]);

        $this->assertEquals("false", $data["gameOver"]);
        $this->assertEquals([1, 1, 1, 1, 1], $data["diceArray"]);
    }
}
