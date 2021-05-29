<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;
use App\Models\Database\ViewResults;
use App\Models\User;

class ResultsHandler
{

    /**
     * Save submitted result
     *
     * @param array $post
     * @property array $post
     * @property string $resultId
     * @return Object
     */
    public function submitResult(array $post): object
    {
        $resultId = $this->saveResult($post);

        $mode = $post['mode'];

        if ($mode == 'challenge') {
            $this->newChallenge($post, $resultId);
            return redirect()->route('highscores');
        }

        if ($mode == 'accept') {
            $this->acceptedChallenge($post, $resultId);
            return redirect()->route('challenge', ['id' => $post['challengeId']]);
        }

        $this->single($post);

        return redirect()->route('highscores');
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
     */
    private function newChallenge(array $post, int $resultId): void
    {
        $challenges = new TableChallenges();
        $users = new User();

        $bet = intval($post['bet']);
        $userId = intval($post['user_id']);
        $opponentId = intval($post['opponent']);

        $challenges->saveNewChallenge($userId, $resultId, $opponentId, $bet);

        $bet = $bet * -1;
        $users->updateBalance($userId, $bet);
    }

    /**
     * Update balance based on result
     *
     * @param array $post
     * @property object $users
     * @property int $bet
     * @property int $userId
     */
    private function single(array $post): void
    {
        $users = new User();

        $score = intval($post['score']);
        $bet = intval($post['bet']);
        $userId = intval($post['user_id']);

        if ($score < 250) {
            $bet = $bet * -1;
        }
        $users->updateBalance($userId, $bet);
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
     */
    private function acceptedChallenge(array $post, int $resultId): void
    {
        $challenges = new TableChallenges();
        $users = new User();
        $results = new ViewResults();

        $score = intval($post['score']);
        $bet = intval($post['bet']);
        $userId = intval($post['user_id']);

        // Get challenger details
        $challengeId = intval($post['challengeId']);
        $challengerResultId = intval($challenges->getChallengerResultId($challengeId));
        $challengerResult = $results->getResult($challengerResultId);
        $challengerId = intval($challengerResult['user_id']);
        $challengerScore = intval($challengerResult['score']);

        //Check balance: if lower than bet, lower bet accordingly
        $userBalance = intval($users->getCoins($userId));
        if ($userBalance < $bet) {
            $difference = $bet - $userBalance;
            $users->updateBalance($challengerId, $difference);
            $bet = $userBalance;
        }

        //Save result and total bet sum to challenges table
        $totalBet = $bet * 2;
        $challenges->updateChallenge($challengeId, $totalBet, $resultId);

        if ($score > $challengerScore) {
            $users->updateBalance($userId, $bet);
            return;
        }

        if ($score < $challengerScore) {
            $bet = $bet * -1;
            $users->updateBalance($userId, $bet);
            $users->updateBalance($challengerId, $totalBet);
            return;
        }

        $users->updateBalance($challengerId, $bet);

        return;
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
    private function saveResult(array $post): int
    {
        $result = new TableResult();
        $histogram = new TableHistogram();

        foreach ($post as $key => $value) {
            $post[$key] = intval($value);
        };

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
