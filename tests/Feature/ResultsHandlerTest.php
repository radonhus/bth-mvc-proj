<?php

// declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;
use App\Models\User;
use App\Models\ResultsHandler;

use ReflectionMethod;

/**
 * Test cases for the ResultsHandler class
 */
class ResultsHandlerTest extends TestCase
{


    /**
     * Test that calling submitResult as single player mode renders a new
     * row in the result and histogram tables.
     *
     * @return void
     */
    public function testSubmitResultAddsRowInDatabaseSingle()
    {
        $resultsHandler = new ResultsHandler();

        $post = [
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
        ];

        $resultsHandler->submitResult($post);

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
     * Test that calling submitResult as challenger player mode renders a new
     * row in the challenge table.
     *
     * @return void
     */
    public function testSubmitResultAddsRowInDatabaseChallenge()
    {
        $resultsHandler = new ResultsHandler();

        $post = [
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
        ];

        $resultsHandler->submitResult($post);

        $challenges = new TableChallenges();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $latestChallengerId = $latestChallenge[0]->challenger_user_id;

        $this->assertEquals('19', $latestChallengerId);
    }

    /**
     * Test that calling submitResult as single player mode renders an opponent
     * result in the corresponding challenges table row
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

        $resultsHandler = new ResultsHandler();

        $post = [
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
        ];

        $resultsHandler->submitResult($post);

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $oppResultId = $latestChallenge[0]->opponent_result_id;

        $resultId = intval($oppResultId);

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

        $resultsHandler = new ResultsHandler();

        $post = [
            'bet' => 0,
            'user_id' => 19,
            'opponent' => 22
        ];

        $publicNewChallenge = new ReflectionMethod(
            'App\Models\ResultsHandler',
            'newChallenge'
        );
        $publicNewChallenge->setAccessible(true);
        $publicNewChallenge->invokeArgs($resultsHandler, [$post, $latestResultId]);

        $challenges = new TableChallenges();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $challId = $latestChallenge[0]->challenger_user_id;

        $this->assertEquals('19', $challId);
    }

    /**
     * Test that calling single saves a new result in the results table
     *
     * @return void
     */
    public function testSaveSingle()
    {
        $users = new User();
        $resultsHandler = new ResultsHandler();

        $userBefore = $users->where('id', 19)
                                ->get();
        $userCoinsBefore = $userBefore[0]->coins;

        $post = ['bet' => 10, 'user_id' => 19, 'score' => 251];

        $publicSingle = new ReflectionMethod(
            'App\Models\ResultsHandler',
            'single'
        );
        $publicSingle->setAccessible(true);
        $publicSingle->invokeArgs($resultsHandler, [$post]);

        $userAfter = $users->where('id', 19)
                                ->get();
        $userCoinsAfter = $userAfter[0]->coins;

        $this->assertGreaterThan($userCoinsBefore, $userCoinsAfter);
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

        $resultsHandler = new ResultsHandler();

        $post = [
            'score' => 0,
            'bet' => 0,
            'user_id' => 19,
            'challengeId' => $latestChallengeId
        ];

        $publicAccChallenge = new ReflectionMethod(
            'App\Models\ResultsHandler',
            'acceptedChallenge'
        );
        $publicAccChallenge->setAccessible(true);
        $publicAccChallenge->invokeArgs($resultsHandler, [$post, $opponentResultId]);

        $challenges = new TableChallenges();

        $latestChallenge = $challenges->orderByDesc('id')
                                ->limit(1)
                                ->get();
        $oppResultId = $latestChallenge[0]->opponent_result_id;

        $resultId = intval($oppResultId);

        $this->assertGreaterThan(0, $resultId);
    }



    /**
     * Test that saveResult adds row with expected value in database
     *
     * @return void
     */
    public function testSaveResultAddsExpectedResultToDatabase()
    {
        $resultsHandler = new ResultsHandler();
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
            'App\Models\ResultsHandler',
            'saveResult'
        );
        $publicSaveResult->setAccessible(true);
        $publicSaveResult->invokeArgs($resultsHandler, [$post]);

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
        $userObject = new User();
        $resultObject = new TableResult();
        $histogramObject = new TableHistogram();
        $challengesObject = new TableChallenges();

        $challengesObject->where('challenger_user_id', 19)->delete();
        $histogramObject->where('dice_6', 999)->delete();

        $userObject->where('user_id', 19)
                    ->update(['coins' => 100]);
        $resultObject->where('user_id', 19)->delete();

        $userObject->where('user_id', 22)
                    ->update(['coins' => 100]);
        $resultObject->where('user_id', 22)->delete();
    }
}
