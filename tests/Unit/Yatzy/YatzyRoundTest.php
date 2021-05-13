<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Round class of the Yatzy game.
 */
class YatzyRoundTest extends TestCase
{

    /**
     * Test if object is instance of Round
     */
    public function testIsInstanceOfRound()
    {
        $testRound = new Round();

        $this->assertInstanceOf("App\Models\Yatzy\Round", $testRound);
    }

    /**
     * Test that rollDice returns array
     */
    public function testRollDiceReturnsArray()
    {
        $testRound = new Round();
        $arrayExpected = $testRound->rollDice([]);

        $this->assertIsArray($arrayExpected);
    }

    /**
     * Test that an empty rollDice array does not change dice values
     */
    public function testRollDiceEmptyList()
    {
        $testRound = new Round();
        $diceValuesBefore = $testRound->getRollsAndValues();
        $diceValuesAfter = $testRound->rollDice([]);

        $this->assertEquals($diceValuesBefore[1], $diceValuesAfter[1]);
        $this->assertEquals($diceValuesBefore[2], $diceValuesAfter[2]);
        $this->assertEquals($diceValuesBefore[3], $diceValuesAfter[3]);
        $this->assertEquals($diceValuesBefore[4], $diceValuesAfter[4]);
        $this->assertEquals($diceValuesBefore[5], $diceValuesAfter[5]);
    }

    /**
     * Test that calling rollDice increments reRollsCounter with +1 per call
     */
    public function testRollDiceCounter()
    {
        $testRound = new Round();
        $rollsInitially = $testRound->getRollsAndValues()[0];

        $testRound->rollDice([]);
        $rollsAfterOne = $testRound->getRollsAndValues()[0];
        $testRound->rollDice([]);
        $rollsAfterTwo = $testRound->getRollsAndValues()[0];
        $testRound->rollDice([]);
        $rollsAfterThree = $testRound->getRollsAndValues()[0];

        $this->assertEquals(0, $rollsInitially);
        $this->assertEquals(1, $rollsAfterOne);
        $this->assertEquals(2, $rollsAfterTwo);
        $this->assertEquals(3, $rollsAfterThree);
    }
}
