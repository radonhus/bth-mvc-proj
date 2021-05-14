<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Result;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Test cases for the Result class of the Yatzy game.
 */
class YatzyResultTest extends TestCase
{
    /**
     * Test that object is instance of Result
     */
    public function testIsInstanceOfResult()
    {
        $resultObject = new Result();

        $this->assertInstanceOf("App\Models\Yatzy\Result", $resultObject);
    }

    /**
     * Test that getAllResults() returns an array
     */
    public function testgetAllResultsReturnsArray()
    {
        $resultObject2 = new Result();
        $arrayOfResults = $resultObject2->getAllResults();

        $this->assertIsArray($arrayOfResults);
    }

    /**
     * Test that saveResult() returns true
     */
    public function testSaveResult()
    {
        $resultObject3 = new Result();

        $result = $resultObject3->saveResult('Test', '999');

        $databaseQuery = $resultObject3->orderByDesc('score')
                                ->limit(1)
                                ->get();

        $score = $databaseQuery[0]['score'];

        $this->assertTrue($result);
        $this->assertEquals($score, '999');
    }

    /**
     * Remove added database record after test
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $resultObject4 = new Result();

        $resultObject4->where('score', '999')->delete();
    }
}
