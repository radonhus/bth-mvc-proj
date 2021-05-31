<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Database\TableResult;
use App\Models\Database\TableHistogram;
use App\Models\Database\TableChallenges;
use App\Models\Database\ViewResults;
use App\Models\Database\TableUsers;

class ResultsHandler
{

    /**
     * Save submitted result
     *
     * @param array $post
     * @property array $post
     * @property string $resultId
     * @return int $resultId
     */
    public function submitResult(array $post): int
    {
        $resultId = $this->saveResult($post);

        $mode = $post['mode'];

        if ($mode == 'challenge') {
            $this->newChallenge($post, $resultId);
            return $resultId;
        }

        if ($mode == 'accept') {
            $this->acceptedChallenge($post, $resultId);
            return $resultId;
        }
        $this->single($post);
        return $resultId;
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
        $users = new TableUsers();

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
        $users = new TableUsers();

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
        $users = new TableUsers();
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

        $result->saveResult($post);

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
