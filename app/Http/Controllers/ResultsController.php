<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;
use App\Models\Database\ViewResults;
use App\Models\Database\ViewChallenges;
use App\Models\User;
use App\Models\ResultsHandler;
use Illuminate\Http\Request;

class ResultsController extends Controller
{

    /**
     * Display results for one game
     *
     * @param int $resultId
     * @return Object
     */
    public function oneResult(int $resultId)
    {
        $presentResult = new ViewResults();

        $result = $presentResult->getResult($resultId);
        $scorecard = $presentResult->getScorecard($resultId);
        $histogram = $presentResult->getHistogram($resultId);

        return view('oneresult', [
            'title' => "Results | ¥atzyBonanza",
            'id' => $resultId,
            'result' => $result,
            'scorecard' => $scorecard,
            'histogram' => $histogram
        ]);
    }

    /**
     * Present top ten results
     *
     * @property object  $presentResult
     * @property array  $topTenArray
     * @property object  $users
     * @property array  $topTenRichest
     * @return Object
     */
    public function highScores()
    {
        $presentResult = new ViewResults();
        $topTenArray = $presentResult->getHighscores();

        $users = new User();
        $topTenRichest = $users->getRichestUsers();

        return view('yatzyhighscores', [
            'title' => "Yatzy | ¥atzyBonanza",
            'highscores' => $topTenArray,
            'richest' => $topTenRichest
        ]);
    }

    /**
     * Save submitted result by calling ResultsHandler class
     *
     * @param Request $request
     * @property array $post
     * @property object  $view
     * @return Object
     */
    public function submitResult(Request $request)
    {
        $post = $request->all();

        $resultsHandler = new ResultsHandler();

        $resultsHandler->submitResult($post);

        session()->put('data', []);

        if ($post['mode'] == 'accept') {
            return redirect()->route('challenge', ['id' => $post['challengeId']]);
        }

        return redirect()->route('highscores');
    }

    /**
     * Display results for one challenge
     *
     * @param int $challengeId
     * @return Object
     */
    public function oneChallenge(int $challengeId)
    {
        $viewChallenges = new ViewChallenges();
        $viewResults = new ViewResults();

        $challenge = $viewChallenges->getOneChallenge(intval($challengeId));
        $challengerResultId = $challenge['challenger_result_id'];
        $opponentResultId = $challenge['opponent_result_id'];

        $resultChallenger = $viewResults->getResult(intval($challengerResultId));
        $scorecardChallenger = $viewResults->getScorecard(intval($challengerResultId));
        $resultOpponent = $viewResults->getResult(intval($opponentResultId));
        $scorecardOpponent = $viewResults->getScorecard(intval($opponentResultId));

        return view('challengeresults', [
            'title' => "Challenge results | ¥atzyBonanza",
            'challenge' => $challenge,
            'resultChallenger' => $resultChallenger,
            'scorecardChallenger' => $scorecardChallenger,
            'resultOpponent' => $resultOpponent,
            'scorecardOpponent' => $scorecardOpponent
        ]);
    }
}
