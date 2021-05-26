<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use ReflectionMethod;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Test cases for the Points class of the Yatzy game.
 */
class YatzyPointsTest extends TestCase
{

    /**
     * Test that instantiated object is instance of Points
     */
    public function testIsInstanceOfPoints()
    {
        $testPoints = new Points();

        $this->assertInstanceOf("App\Models\Yatzy\Points", $testPoints);
    }

    /**
     * Test that object has the diceArray property
     */
    public function testHasPointsArray()
    {
        $testPoints = new Points();

        $this->assertObjectHasAttribute("pointsArray", $testPoints);
    }

    /**
     * Test that getPointsArray returns array of integers
     */
    public function testGetPointsArrayReturnsArray()
    {
        $testPoints = new Points();
        $testPointsArray = $testPoints->getPointsArray();

        $this->assertIsInt($testPointsArray["one_pair"]);
        $this->assertIsInt($testPointsArray["chance"]);
        $this->assertIsInt($testPointsArray["yatzy"]);
        $this->assertIsInt($testPointsArray["three"]);
    }

    /**
     * Test that calcTotalPoints returns expected sum
     */
    public function testCalcTotalPointsReturnsSum()
    {
        $testPoints = new Points();
        $testPointsSum = $testPoints->calcTotalPoints();

        $this->assertIsInt($testPointsSum);
        $this->assertEquals(0, $testPointsSum);
    }

    /**
     * Test that calcBonusPoints returns expected points
     */
    public function testCalcBonusPointsReturnsSum()
    {
        $testPoints = new Points();
        $testPointsBonus = $testPoints->calcBonusPoints();

        $this->assertIsInt($testPointsBonus);
        $this->assertEquals(0, $testPointsBonus);
    }

    /**
     * Test that getRoundPoints returns expected points
     */
    public function testGetRoundPointsReturnsSum()
    {
        $testPoints = new Points();

        $fullHouse = $testPoints->getRoundPoints([6, 6, 6, 1, 1], "full_house");
        $noFullHouse = $testPoints->getRoundPoints([6, 6, 3, 1, 1], "full_house");

        $onePair = $testPoints->getRoundPoints([6, 6, 6, 1, 1], "one_pair");
        $onePairReverse = $testPoints->getRoundPoints([1, 1, 6, 6, 6], "one_pair");
        $noOnePair = $testPoints->getRoundPoints([6, 5, 4, 3, 2], "one_pair");

        $this->assertIsInt($onePair);
        $this->assertEquals(12, $onePair);
        $this->assertIsInt($onePairReverse);
        $this->assertEquals(12, $onePairReverse);
        $this->assertIsInt($noOnePair);
        $this->assertEquals(0, $noOnePair);
    }

    /**
     * Test that calcYatzy returns expected sum
     */
    public function testCalcYatzy()
    {
        $testPoints = new Points();

        $calcYatzy = new ReflectionMethod('App\Models\Yatzy\Points', 'calcYatzy');
        $calcYatzy->setAccessible(true);

        $diceYatzy1 = $calcYatzy->invokeArgs($testPoints, [[6, 6, 6, 6, 6]]);
        $diceYatzy2 = $calcYatzy->invokeArgs($testPoints, [[1, 1, 1, 1, 1]]);
        $diceYatzy3 = $calcYatzy->invokeArgs($testPoints, [[6, 6, 6, 6, 5]]);

        $this->assertIsInt($diceYatzy1);
        $this->assertEquals(50, $diceYatzy1);
        $this->assertIsInt($diceYatzy2);
        $this->assertEquals(50, $diceYatzy2);
        $this->assertIsInt($diceYatzy3);
        $this->assertEquals(0, $diceYatzy3);
    }

    /**
     * Test that calcChance returns expected sum
     */
    public function testCalcChance()
    {
        $testPoints = new Points();

        $calcChance = new ReflectionMethod('App\Models\Yatzy\Points', 'calcChance');
        $calcChance->setAccessible(true);

        $diceChance1 = $calcChance->invokeArgs($testPoints, [[6, 6, 6, 6, 6]]);
        $diceChance2 = $calcChance->invokeArgs($testPoints, [[1, 1, 1, 1, 1]]);
        $diceChance3 = $calcChance->invokeArgs($testPoints, [[6, 6, 6, 6, 5]]);

        $this->assertIsInt($diceChance1);
        $this->assertEquals(30, $diceChance1);
        $this->assertIsInt($diceChance2);
        $this->assertEquals(5, $diceChance2);
        $this->assertIsInt($diceChance3);
        $this->assertEquals(29, $diceChance3);
    }

    /**
     * Test that calcFullHouse returns expected sum
     */
    public function testCalcFullHouse()
    {
        $testPoints = new Points();

        $calcFullHouse = new ReflectionMethod('App\Models\Yatzy\Points', 'calcFullHouse');
        $calcFullHouse->setAccessible(true);

        $diceFullHouse1 = $calcFullHouse->invokeArgs($testPoints, [[6, 6, 5, 5, 5]]);
        $diceFullHouse2 = $calcFullHouse->invokeArgs($testPoints, [[2, 2, 1, 1, 1]]);
        $diceFullHouse3 = $calcFullHouse->invokeArgs($testPoints, [[6, 6, 6, 6, 5]]);

        $this->assertIsInt($diceFullHouse1);
        $this->assertEquals(27, $diceFullHouse1);
        $this->assertIsInt($diceFullHouse2);
        $this->assertEquals(7, $diceFullHouse2);
        $this->assertIsInt($diceFullHouse3);
        $this->assertEquals(0, $diceFullHouse3);
    }

    /**
     * Test that calcLargeStraight returns expected sum
     */
    public function testCalcLargeStraight()
    {
        $testPoints = new Points();

        $calcLargeStraight = new ReflectionMethod('App\Models\Yatzy\Points', 'calcLargeStraight');
        $calcLargeStraight->setAccessible(true);

        $diceLargeStraight1 = $calcLargeStraight->invokeArgs($testPoints, [[6, 5, 4, 3, 2]]);
        $diceLargeStraight2 = $calcLargeStraight->invokeArgs($testPoints, [[5, 4, 3, 2, 1]]);
        $diceLargeStraight3 = $calcLargeStraight->invokeArgs($testPoints, [[6, 6, 6, 6, 5]]);

        $this->assertIsInt($diceLargeStraight1);
        $this->assertEquals(20, $diceLargeStraight1);
        $this->assertIsInt($diceLargeStraight2);
        $this->assertEquals(0, $diceLargeStraight2);
        $this->assertIsInt($diceLargeStraight3);
        $this->assertEquals(0, $diceLargeStraight3);
    }

    /**
     * Test that calcSmallStraight returns expected sum
     */
    public function testCalcSmallStraight()
    {
        $testPoints = new Points();

        $calcSmallStraight = new ReflectionMethod('App\Models\Yatzy\Points', 'calcSmallStraight');
        $calcSmallStraight->setAccessible(true);

        $diceSmallStraight1 = $calcSmallStraight->invokeArgs($testPoints, [[6, 5, 4, 3, 2]]);
        $diceSmallStraight2 = $calcSmallStraight->invokeArgs($testPoints, [[5, 4, 3, 2, 1]]);
        $diceSmallStraight3 = $calcSmallStraight->invokeArgs($testPoints, [[6, 6, 6, 6, 5]]);

        $this->assertIsInt($diceSmallStraight1);
        $this->assertEquals(0, $diceSmallStraight1);
        $this->assertIsInt($diceSmallStraight2);
        $this->assertEquals(15, $diceSmallStraight2);
        $this->assertIsInt($diceSmallStraight3);
        $this->assertEquals(0, $diceSmallStraight3);
    }

    /**
     * Test that calcFour returns expected sum
     */
    public function testCalcFour()
    {
        $testPoints = new Points();

        $calcFour = new ReflectionMethod('App\Models\Yatzy\Points', 'calcFour');
        $calcFour->setAccessible(true);

        $diceFour1 = $calcFour->invokeArgs($testPoints, [[4, 4, 4, 4, 2]]);
        $diceFour2 = $calcFour->invokeArgs($testPoints, [[5, 5, 5, 5, 1]]);
        $diceFour3 = $calcFour->invokeArgs($testPoints, [[1, 1, 1, 6, 6]]);

        $this->assertIsInt($diceFour1);
        $this->assertEquals(16, $diceFour1);
        $this->assertIsInt($diceFour2);
        $this->assertEquals(20, $diceFour2);
        $this->assertIsInt($diceFour3);
        $this->assertEquals(0, $diceFour3);
    }

    /**
     * Test that calcThree returns expected sum
     */
    public function testCalcThree()
    {
        $testPoints = new Points();

        $calcThree = new ReflectionMethod('App\Models\Yatzy\Points', 'calcThree');
        $calcThree->setAccessible(true);

        $diceThree1 = $calcThree->invokeArgs($testPoints, [[4, 4, 4, 4, 2]]);
        $diceThree2 = $calcThree->invokeArgs($testPoints, [[5, 5, 5, 6, 1]]);
        $diceThree3 = $calcThree->invokeArgs($testPoints, [[1, 1, 1, 6, 6]]);

        $this->assertIsInt($diceThree1);
        $this->assertEquals(12, $diceThree1);
        $this->assertIsInt($diceThree2);
        $this->assertEquals(15, $diceThree2);
        $this->assertIsInt($diceThree3);
        $this->assertEquals(3, $diceThree3);
    }

    /**
     * Test that calcTwoPairs returns expected sum
     */
    public function testCalcTwoPairs()
    {
        $testPoints = new Points();

        $calcTwoPairs = new ReflectionMethod('App\Models\Yatzy\Points', 'calcTwoPairs');
        $calcTwoPairs->setAccessible(true);

        $diceTwoPairs1 = $calcTwoPairs->invokeArgs($testPoints, [[4, 4, 4, 4, 2]]);
        $diceTwoPairs2 = $calcTwoPairs->invokeArgs($testPoints, [[5, 5, 5, 4, 1]]);
        $diceTwoPairs3 = $calcTwoPairs->invokeArgs($testPoints, [[6, 6, 1, 1, 1]]);

        $this->assertIsInt($diceTwoPairs1);
        $this->assertEquals(16, $diceTwoPairs1);
        $this->assertIsInt($diceTwoPairs2);
        $this->assertEquals(0, $diceTwoPairs2);
        $this->assertIsInt($diceTwoPairs3);
        $this->assertEquals(14, $diceTwoPairs3);
    }

    /**
     * Test that calcOnePair returns expected sum
     */
    public function testCalcOnePair()
    {
        $testPoints = new Points();

        $calcOnePair = new ReflectionMethod('App\Models\Yatzy\Points', 'calcOnePair');
        $calcOnePair->setAccessible(true);

        $diceOnePair1 = $calcOnePair->invokeArgs($testPoints, [[4, 4, 4, 4, 2]]);
        $diceOnePair2 = $calcOnePair->invokeArgs($testPoints, [[5, 5, 5, 4, 1]]);
        $diceOnePair3 = $calcOnePair->invokeArgs($testPoints, [[6, 6, 1, 1, 1]]);

        $this->assertIsInt($diceOnePair1);
        $this->assertEquals(8, $diceOnePair1);
        $this->assertIsInt($diceOnePair2);
        $this->assertEquals(10, $diceOnePair2);
        $this->assertIsInt($diceOnePair3);
        $this->assertEquals(12, $diceOnePair3);
    }

    /**
     * Test that calc16 returns expected sum
     */
    public function testCalc16()
    {
        $testPoints = new Points();

        $calc16 = new ReflectionMethod('App\Models\Yatzy\Points', 'calc16');
        $calc16->setAccessible(true);

        $dice161 = $calc16->invokeArgs($testPoints, [[4, 4, 4, 4, 2], "4"]);
        $dice162 = $calc16->invokeArgs($testPoints, [[5, 5, 5, 4, 1], "4"]);
        $dice163 = $calc16->invokeArgs($testPoints, [[6, 6, 1, 1, 1], "5"]);

        $this->assertIsInt($dice161);
        $this->assertEquals(16, $dice161);
        $this->assertIsInt($dice162);
        $this->assertEquals(4, $dice162);
        $this->assertIsInt($dice163);
        $this->assertEquals(0, $dice163);
    }
}
