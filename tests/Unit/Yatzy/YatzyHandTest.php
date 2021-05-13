<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Hand class of the Yatzy game.
 */
class YatzyHandTest extends TestCase
{

    /**
     * Test that object is instance of Hand
     */
    public function testIsInstanceOfHand()
    {
        $testHand = new Hand();

        $this->assertInstanceOf("App\Models\Yatzy\Hand", $testHand);
    }

    /**
     * Test that object has the diceArray attribute
     */
    public function testHasDiceArray()
    {
        $testHand = new Hand();

        $this->assertObjectHasAttribute("diceArray", $testHand);
    }

    /**
     * Test that rollSelectedDice returns array with five integers
     */
    public function testRollSelectedDiceReturnsArrayOfIntValues()
    {
        $testHand = new Hand();
        $testDiceValues = $testHand->rollSelectedDice([0, 1, 2, 3, 4]);

        $this->assertIsInt($testDiceValues[0]);
        $this->assertIsInt($testDiceValues[1]);
        $this->assertIsInt($testDiceValues[2]);
        $this->assertIsInt($testDiceValues[3]);
        $this->assertIsInt($testDiceValues[4]);
    }

    /**
     * Test that getDiceValues returns array with five integers
     */
    public function testGetDiceValuesReturnsArrayOfIntValues()
    {
        $testHand = new Hand();
        $testDiceValues = $testHand->getDiceValues();

        $this->assertIsInt($testDiceValues[0]);
        $this->assertIsInt($testDiceValues[1]);
        $this->assertIsInt($testDiceValues[2]);
        $this->assertIsInt($testDiceValues[3]);
        $this->assertIsInt($testDiceValues[4]);
    }
}
