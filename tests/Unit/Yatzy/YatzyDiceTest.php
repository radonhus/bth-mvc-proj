<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Dice class of the Yatzy game.
 */
class YatzyDiceTest extends TestCase
{

    /**
     * Test if object is instance of Dice
     */
    public function testInstanceOfDice()
    {
        $testDice = new Dice();
        $this->assertInstanceOf("App\Models\Yatzy\Dice", $testDice);
    }

    /**
     * Test if value is integer between > 0 and < 7
     */
    public function testDiceValue()
    {
        $testDice = new Dice();
        $this->assertIsInt($testDice->getDiceValue());
        $this->assertGreaterThanOrEqual(1, $testDice->getDiceValue());
        $this->assertLessThanOrEqual(6, $testDice->getDiceValue());
    }
}
