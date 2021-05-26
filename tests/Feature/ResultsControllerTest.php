<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\ResultsController;
use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;

use ReflectionMethod;

class ResultsControllerTest extends TestCase
{

    /**
     * Test that instantiated object is instance of ResultsController
     */
    public function testInstantiateResultsController()
    {
        $controller = new ResultsController();

        $this->assertInstanceOf("App\Http\Controllers\ResultsController", $controller);
    }

    /**
     * Check that ResultsController class extends Controller
     */
    public function testResultsControllerExtendsController()
    {
        $controller = new ResultsController();

        $this->assertInstanceOf("App\Http\Controllers\Controller", $controller);
    }

    /**
     * Test that oneResult returns view object
     *
     * @return void
     */
    public function testOneResultReturnsView()
    {
        $results = new TableResult();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();

        $latestResultId = $latestResult[0]->id;

        $controller = new ResultsController();

        $response = $controller->oneResult($latestResultId);

        $this->assertInstanceOf("Illuminate\Contracts\View\View", $response);
    }

    /**
     * Test that highScores returns view object
     *
     * @return void
     */
    public function testHighScoresReturnsViewObject()
    {
        $results = new ResultsController();

        $callResult = $results->highScores();

        $this->assertInstanceOf("Illuminate\Contracts\View\View", $callResult);
    }


    /**
     * Test that the post route /highscores works = trying to submit
     * a new single result renders a new row in the result table and in the histogram
     * table.
     *
     * @return void
     */
    public function testSubmitResultAddsRowInDatabaseSingle()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/highscores', [
            'mode' => 'single',
            'score' => 0,
            'bet' => 0,
            'user_id' => 19,
            'opponent' => 0,
            'challengeId' => 0,
            'bonus' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            'one_pair' => 0,
            'two_pairs' => 0,
            'three' => 0,
            'four' => 0,
            'small_straight' => 0,
            'large_straight' => 0,
            'full_house' => 0,
            'chance' => 0,
            'yatzy' => 999,
            'dice_1' => 0,
            'dice_2' => 0,
            'dice_3' => 0,
            'dice_4' => 0,
            'dice_5' => 0,
            'dice_6' => 999
        ]);

        $histograms = new TableHistogram();
        $results = new TableResult();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestYatzyResult = $latestResult[0]->result_yatzy;

        $latestHistogram = $histograms->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestDice6Histogram = $latestHistogram[0]->dice_6;

        $this->assertEquals('999', $latestYatzyResult);
        $this->assertEquals('999', $latestDice6Histogram);
    }

    /**
     * Test that the post route /highscores works = trying to submit
     * a new challenge result renders a new row in the challenge table
     *
     * @return void
     */
    public function testSubmitResultAddsRowInDatabaseChallenge()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/highscores', [
            'mode' => 'challenge',
            'score' => 0,
            'bet' => 0,
            'user_id' => 19,
            'opponent' => 22,
            'challengeId' => 0,
            'bonus' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            'one_pair' => 0,
            'two_pairs' => 0,
            'three' => 0,
            'four' => 0,
            'small_straight' => 0,
            'large_straight' => 0,
            'full_house' => 0,
            'chance' => 0,
            'yatzy' => 999,
            'dice_1' => 0,
            'dice_2' => 0,
            'dice_3' => 0,
            'dice_4' => 0,
            'dice_5' => 0,
            'dice_6' => 999
        ]);

        $challenges = new TableChallenges();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengerId = $latestChallenge[0]->challenger_user_id;

        $this->assertEquals('19', $latestChallengerId);
    }

    /**
     * Test that the post route /highscores works = trying to submit
     * a new challenge accepted result renders an opponent result in the
     * corresponding challenges table row
     *
     * @return void
     */
    public function testSubmitResultAddsRowInDatabaseAccept()
    {
        $results = new TableResult();
        $challenges = new TableChallenges();

        $results->user_id = 19;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestResultId = $latestResult[0]->id;

        $challenges->bet = 0;
        $challenges->challenger_user_id = 19;
        $challenges->challenger_result_id = $latestResultId;
        $challenges->opponent_user_id = 22;
        $challenges->save();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengeId = $latestChallenge[0]->id;

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/highscores', [
            'mode' => 'accept',
            'score' => 0,
            'bet' => 0,
            'user_id' => 22,
            'opponent' => 19,
            'challengeId' => $latestChallengeId,
            'bonus' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            'one_pair' => 0,
            'two_pairs' => 0,
            'three' => 0,
            'four' => 0,
            'small_straight' => 0,
            'large_straight' => 0,
            'full_house' => 0,
            'chance' => 0,
            'yatzy' => 999,
            'dice_1' => 0,
            'dice_2' => 0,
            'dice_3' => 0,
            'dice_4' => 0,
            'dice_5' => 0,
            'dice_6' => 999
        ]);

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengeOppResultId = $latestChallenge[0]->opponent_result_id;

        $resultId = intval($latestChallengeOppResultId);

        $this->assertGreaterThan(0, $resultId);
    }

    /**
     * Test that calling newChallenge saves a new challenge in the challenge table
     *
     * @return void
     */
    public function testSaveNewChallenge()
    {
        $results = new TableResult();

        $results->user_id = 19;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestResultId = $latestResult[0]->id;

        $resultsController = new ResultsController();

        $post = [
            'bet' => 0,
            'user_id' => 19,
            'opponent' => 22
        ];

        $publicNewChallenge = new ReflectionMethod(
            'App\Http\Controllers\ResultsController',
            'newChallenge');
        $publicNewChallenge->setAccessible(true);
        $publicNewChallenge->invokeArgs($resultsController, [$post, $latestResultId]);

        $challenges = new TableChallenges();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengeChallengerId = $latestChallenge[0]->challenger_user_id;

        $this->assertEquals('19', $latestChallengeChallengerId);
    }

    /**
     * Test that calling single saves a new result in the results table
     *
     * @return void
     */
    public function testSaveSingle()
    {
        $results = new TableResult();

        $results->user_id = 19;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestResultId = $latestResult[0]->id;

        $resultsController = new ResultsController();

        $post = [
            'bet' => 0,
            'user_id' => 19,
            'score' => 0
        ];

        $publicSingle = new ReflectionMethod(
            'App\Http\Controllers\ResultsController',
            'single');
        $publicSingle->setAccessible(true);
        $publicSingle->invokeArgs($resultsController, [$post, $latestResultId]);

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestResultUserId = $latestResult[0]->user_id;

        $this->assertEquals('19', $latestResultUserId);
    }


    /**
     * Test that calling acceptedChallenge alters the challenge
     *
     * @return void
     */
    public function testAcceptedhallenge()
    {
        $results = new TableResult();

        $results->user_id = 19;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $challengerResultId = $latestResult[0]->id;

        $challenges = new TableChallenges();

        $challenges->bet = 0;
        $challenges->challenger_user_id = 19;
        $challenges->challenger_result_id = $challengerResultId;
        $challenges->opponent_user_id = 22;
        $challenges->save();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengeId = $latestChallenge[0]->id;

        $results->user_id = 22;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $opponentResultId = $latestResult[0]->id;

        $resultsController = new ResultsController();

        $post = [
            'score' => 0,
            'bet' => 0,
            'user_id' => 19,
            'challengeId' => $latestChallengeId
        ];

        $publicAcceptedChallenge = new ReflectionMethod(
            'App\Http\Controllers\ResultsController',
            'acceptedChallenge');
        $publicAcceptedChallenge->setAccessible(true);
        $publicAcceptedChallenge->invokeArgs($resultsController, [$post, $opponentResultId]);

        $challenges = new TableChallenges();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengeOppResultId = $latestChallenge[0]->opponent_result_id;

        $resultId = intval($latestChallengeOppResultId);

        $this->assertGreaterThan(0, $resultId);
    }

    /**
     * Test that oneChallenge returns view object
     *
     * @return void
     */
    public function testOneChallengeReturnsViewObject()
    {
        $challenges = new TableChallenges();
        $results = new TableResult();

        $results->user_id = 19;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $challengerResultId = $latestResult[0]->id;

        $challenges->bet = 0;
        $challenges->challenger_user_id = 19;
        $challenges->challenger_result_id = $challengerResultId;
        $challenges->opponent_user_id = 22;
        $challenges->save();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengeId = $latestChallenge[0]->id;

        $results->user_id = 22;
        $results->result_bonus = 0;
        $results->result_1 = 0;
        $results->result_2 = 0;
        $results->result_3 = 0;
        $results->result_4 = 0;
        $results->result_5 = 0;
        $results->result_6 = 0;
        $results->result_one_pair = 0;
        $results->result_two_pairs = 0;
        $results->result_three = 0;
        $results->result_four = 0;
        $results->result_small_straight = 0;
        $results->result_large_straight = 0;
        $results->result_full_house = 0;
        $results->result_chance = 0;
        $results->result_yatzy = 999;
        $results->save();

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $opponentResultId = $latestResult[0]->id;

        $challenges->where('id', $latestChallengeId)
                    ->update(['opponent_result_id' => $opponentResultId]);

        $resultsController = new ResultsController();

        $callResult = $resultsController->oneChallenge($latestChallengeId);

        $this->assertInstanceOf("Illuminate\Contracts\View\View", $callResult);
    }



    /**
     * Test that saveResult adds row with expected value in database
     *
     * @return void
     */
    public function testSaveResultAddsExpecteResultToDatabase()
    {
        $resultsController = new ResultsController();
        $results = new TableResult();
        $histograms = new TableHistogram();

        $post = [
            'user_id' => 19,
            'bonus' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            'one_pair' => 0,
            'two_pairs' => 0,
            'three' => 0,
            'four' => 0,
            'small_straight' => 0,
            'large_straight' => 0,
            'full_house' => 0,
            'chance' => 0,
            'yatzy' => 999,
            'dice_1' => 0,
            'dice_2' => 0,
            'dice_3' => 0,
            'dice_4' => 0,
            'dice_5' => 0,
            'dice_6' => 999
        ];

        $publicSaveResult = new ReflectionMethod(
            'App\Http\Controllers\ResultsController',
            'saveResult');
        $publicSaveResult->setAccessible(true);
        $publicSaveResult->invokeArgs($resultsController, [$post]);

        $latestResult = $results->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestYatzyResult = $latestResult[0]->result_yatzy;

        $latestHistogram = $histograms->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestDice6Histogram = $latestHistogram[0]->dice_6;

        $this->assertEquals('999', $latestYatzyResult);
        $this->assertEquals('999', $latestDice6Histogram);
    }

    /**
     * Remove added database record after test
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $resultObject = new TableResult();
        $histogramObject = new TableHistogram();
        $challengesObject = new TableChallenges();

        $challengesObject->where('challenger_user_id', 19)->delete();
        $histogramObject->where('dice_6', 999)->delete();
        $resultObject->where('user_id', 19)->delete();
        $resultObject->where('user_id', 22)->delete();
    }
}
