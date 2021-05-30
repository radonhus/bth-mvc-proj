<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Controllers\ResultsController;
use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;
use App\Models\User;

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
     * Test that the post route /highscores works = submitting a result
     * returns a redirect (302)
     *
     * @return void
     */
    public function testSubmitResultReturns302Single()
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

        $response->assertStatus(302);
        $response->assertSee('Redirect');
    }

    /**
     * Test that the post route /highscores works = submitting a result
     * with mode set to 'accept' returns a redirect (302)
     *
     * @return void
     */
    public function testSubmitResultReturns302Accept()
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
            'opponent' => 0,
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

        $response->assertStatus(302);
        $response->assertSee('Redirect');
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
     * Remove added database record after test
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $userObject = new User();
        $resultObject = new TableResult();
        $histogramObject = new TableHistogram();
        $challengesObject = new TableChallenges();

        $challengesObject->where('challenger_user_id', 19)->delete();

        $histogramObject->where('dice_6', 999)->delete();

        $userObject->where('user_id', 19)->update(['coins' => 100]);
        $userObject->where('user_id', 22)->update(['coins' => 100]);

        $resultObject->where('user_id', 22)->delete();
        $resultObject->where('user_id', 19)->delete();
    }
}
