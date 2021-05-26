<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Histogram class of the Yatzy game.
 */
class YatzyHistogramTest extends TestCase
{

    /**
     * Test that object is instance of Histogram
     */
    public function testIsInstanceOfHistogram()
    {
        $testHistogram = new Histogram();

        $this->assertInstanceOf("App\Models\Yatzy\Histogram", $testHistogram);
    }

    /**
     * Test that object has the histogramArray attribute
     */
    public function testHasHistogramArray()
    {
        $testHistogram = new Histogram();

        $this->assertObjectHasAttribute("histogramArray", $testHistogram);
    }

    /**
     * Test that rollSelectedDice returns array with five integers
     */
    public function testSaveReturnsSameDiceArray()
    {
        $testHistogram = new Histogram();

        $testDiceValues = $testHistogram->save([6, 1, 5, 3, 4]);

        $this->assertEquals([6, 1, 5, 3, 4], $testDiceValues);
    }

    /**
     * Test that rollSelectedDice returns array with five integers
     */
    public function testGetDiceFrequencyReturnsExpectedArray()
    {
        $testHistogram = new Histogram();

        $testHistogram->save([6, 1, 5, 3, 4]);
        $testHistogram->save([2, 2, 5, 6, 4]);
        $testHistogram->save([4, 4, 4, 3, 4]);
        $testHistogram->save([2, 2, 6, 6, 1]);

        $testFrequency = $testHistogram->getDiceFrequency();

        $this->assertEquals(4, $testFrequency["6"]);
        $this->assertEquals(2, $testFrequency["5"]);
        $this->assertEquals(6, $testFrequency["4"]);
        $this->assertEquals(2, $testFrequency["3"]);
        $this->assertEquals(4, $testFrequency["2"]);
        $this->assertEquals(2, $testFrequency["1"]);
    }
}
