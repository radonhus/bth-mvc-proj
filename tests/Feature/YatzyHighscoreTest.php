<?php

declare(strict_types=1);

namespace App\Models\Yatzy;

use App\Models\Yatzy\Highscore;

use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Test cases for the Highscore class of the Yatzy game.
 */
class YatzyHighscoreTest extends TestCase
{
    /**
     * Test that object is instance of Highscore
     */
    public function testIsInstanceOfHighscore()
    {
        $highscoreObject = new Highscore();

        $this->assertInstanceOf("App\Models\Yatzy\Highscore", $highscoreObject);
    }

    /**
     * Test that getAllHighscores() returns an array
     */
    public function testgetAllHighscoresReturnsArray()
    {
        $highscoreObject2 = new Highscore();
        $arrayOfHighscores = $highscoreObject2->getAllHighscores();

        $this->assertIsArray($arrayOfHighscores);
    }

    /**
     * Test that saveResult() returns true
     */
    public function testSaveResult()
    {
        $highscoreObject3 = new Highscore();

        $result = $highscoreObject3->saveResult('Test', '999');

        $databaseQuery = $highscoreObject3->orderByDesc('score')
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
        $highscoreObject4 = new Highscore();

        $highscoreObject4->where('score', '999')->delete();
    }
}
