<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;
use App\Models\Database\ViewResults;
use App\Models\Database\ViewChallenges;
use App\Models\User;
use Illuminate\Http\Request;

class ResultsController extends Controller
{

    /**
     * Display results for one game
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function oneResult(int $id)
    {
        $presentResult = new ViewResults();

        $result = $presentResult->getResult($id);
        $scorecard = $presentResult->getScorecard($id);
        $histogram = $presentResult->getHistogram($id);

        return view('oneresult', [
            'title' => "Results | ¥atzyBonanza",
            'id' => $id,
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
     * @return \Illuminate\Contracts\View\View
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
     * Save submitted result
     *
     * @param Request $request
     * @property array $post
     * @property string $resultId
     * @property object  $view
     * @return \Illuminate\Contracts\View\View
     */
    public function submitResult(Request $request)
    {
        $post = $request->all();

        $resultId = $this->saveResult($post);

        $mode = $post['mode'];

        if ($mode == 'challenge') {
            $view = $this->newChallenge($post, $resultId);
            return $view;
        }

        if ($mode == 'accept') {
            $view = $this->acceptedChallenge($post, $resultId);
            return $view;
        }

        $view = $this->single($post, $resultId);

        return $view;
    }

    /**
     * Create new challenge and update balance
     *
     * @param array $post
     * @param int $resultId
     * @property object $challenges
     * @property object $users
     * @property string $bet
     * @property string $userId
     * @property string $opponentId
     * @property object $view
     * @return \Illuminate\Contracts\View\View
     */
    private function newChallenge(array $post, int $resultId)
    {
        $challenges = new TableChallenges();
        $users = new User();

        $bet = intval($post['bet']);
        $userId = $post['user_id'];
        $opponentId = $post['opponent'];

        $challenges->saveNewChallenge($userId, $resultId, $opponentId, $bet);

        $bet = $bet * -1;
        $users->updateBalance($userId, $bet);

        $view = $this->highScores();

        return $view;
    }

    /**
     * Update balance based on result
     *
     * @param array $post
     * @param int $resultId
     * @property object $users
     * @property int $bet
     * @property string $userId
     * @property object $view
     * @return \Illuminate\Contracts\View\View
     */
    private function single(array $post, int $resultId)
    {
        $users = new User();

        $score = intval($post['score']);
        $bet = intval($post['bet']);
        $userId = $post['user_id'];

        if ($score < 250) {
            $bet = $bet * -1;
        }
        $users->updateBalance($userId, $bet);

        $view = $this->highScores();

        return $view;
    }

    /**
     * Mark challenge as accepted, check winner and update balance
     *
     * @param array $post
     * @param int $resultId
     * @property object $challenges
     * @property object $users
     * @property object $results
     * @property int $score
     * @property int $bet
     * @property string $userId
     * @property string $challengeId
     * @property int $challengerResultId
     * @property int $challengerId
     * @property int $challengerScore
     * @property int $userBalance
     * @property int $totalBet
     * @property object $view
     * @return \Illuminate\Contracts\View\View
     */
    private function acceptedChallenge(array $post, int $resultId)
    {
        $challenges = new TableChallenges();
        $users = new User();
        $results = new ViewResults();

        $score = intval($post['score']);
        $bet = intval($post['bet']);
        $userId = $post['user_id'];

        // Get challenger details
        $challengeId = $post['challengeId'];
        $challengerResultId = intval($challenges->getChallengerResultId($challengeId));
        $challengerResult = $results->getResult($challengerResultId);
        $challengerId = intval($challengerResult['user_id']);
        $challengerScore = intval($challengerResult['score']);

        //Check balance: if lower than bet, lower bet accordingly
        $userBalance = intval($users->getCoins($userId));
        if ($userBalance < $bet) {
            $bet = $userBalance;
        }

        //Save result and total bet sum to challenges table
        $totalBet = $bet * 2;
        $challenges->updateChallenge($challengeId, $totalBet, $resultId);

        if ($score > $challengerScore) {
            $users->updateBalance($userId, $bet);
        } elseif ($score < $challengerScore) {
            $bet = $bet * -1;
            $users->updateBalance($userId, $bet);
            $users->updateBalance($challengerId, $totalBet);
        } else {
            $users->updateBalance($challengerId, $bet);
        }

        return $this->oneChallenge($challengeId);
    }

    /**
     * Display results for one challenge
     *
     * @param int $challengeId
     * @return \Illuminate\Contracts\View\View
     */
    public function oneChallenge(int $challengeId)
    {
        $viewChallenges = new ViewChallenges();
        $viewResults = new ViewResults();

        $challenge = $viewChallenges->getOneChallenge($challengeId);
        $challengerResultId = $challenge['challenger_result_id'];
        $opponentResultId = $challenge['opponent_result_id'];

        $resultChallenger = $viewResults->getResult($challengerResultId);
        $scorecardChallenger = $viewResults->getScorecard($challengerResultId);
        $resultOpponent = $viewResults->getResult($opponentResultId);
        $scorecardOpponent = $viewResults->getScorecard($opponentResultId);

        return view('challengeresults', [
            'title' => "Challenge results | ¥atzyBonanza",
            'challenge' => $challenge,
            'resultChallenger' => $resultChallenger,
            'scorecardChallenger' => $scorecardChallenger,
            'resultOpponent' => $resultOpponent,
            'scorecardOpponent' => $scorecardOpponent
        ]);
    }

    /**
     * Save submitted result in result table and histogram table.
     *
     * @param array $post
     * @property object $result
     * @property object $histogram
     * @property object $challenges
     * @property int $resultId
     * @return int $resultId
     */
    private function saveResult(array $post)
    {
        $result = new TableResult();
        $histogram = new TableHistogram();

        $result->saveResult(
            $post['user_id'],
            $post['bonus'],
            $post['1'],
            $post['2'],
            $post['3'],
            $post['4'],
            $post['5'],
            $post['6'],
            $post['one_pair'],
            $post['two_pairs'],
            $post['three'],
            $post['four'],
            $post['small_straight'],
            $post['large_straight'],
            $post['full_house'],
            $post['chance'],
            $post['yatzy']
        );

        $resultId = $result->getLatestResult()->id;

        $histogram->saveHistogram(
            $resultId,
            $post['dice_1'],
            $post['dice_2'],
            $post['dice_3'],
            $post['dice_4'],
            $post['dice_5'],
            $post['dice_6']
        );

        return $resultId;
    }
}
