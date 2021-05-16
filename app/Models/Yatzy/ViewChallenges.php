<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property string $challenge_id
* @property string $time
* @property string $bet
* @property string $challenger_result_id
* @property string $challenger_id
* @property string $challenger_name
* @property string $challenger_score
* @property string $opponent_result_id
* @property string $opponent_id
* @property string $opponent_name
* @property string $opponent_score
* @property string $winner
*/
class ViewChallenges extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_challenges';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all open challenges where user has been challenged
     *
     * @param string $userId
     * @property object $result
     * @property array $openChallengesSent
     * @return array $openChallengesSent
     */
    public function getOpenChallengesSent($userId): array
    {
        $result = $this->where(['challenger_id' => $userId, 'opponent_result_id' => null])
                                ->get();

        $openChallengesSent = [];

        foreach ($result as $row) {
            array_push($openChallengesSent, [
                'id' => $row->id,
                'time' => $row->time,
                'bet' => $row->bet,
                'opponent_id' => $row->opponent_id,
                'opponent_name' => $row->opponent_name
            ]);
        }

        return $openChallengesSent;
    }

    /**
     * Get all open challenges where user has been challenged
     *
     * @param string $userId
     * @property object $result
     * @property array $openChallenges
     * @return array $openChallenges
     */
    public function getOpenChallenges($userId): array
    {
        $result = $this->where(['opponent_id' => $userId, 'opponent_result_id' => null])
                                ->get();

        $openChallenges = [];

        foreach ($result as $row) {
            array_push($openChallenges, [
                'id' => $row->id,
                'time' => $row->time,
                'bet' => $row->bet,
                'challenger_id' => $row->challenger_id,
                'challenger_name' => $row->challenger_name
            ]);
        }

        return $openChallenges;
    }

    /**
     * Get all challenges for one player from the v_challenges view
     *
     * @param int $id
     * @property array $result
     * @property array $challenge
     * @return array $challenge
     */
    public function getOneChallenge($id)
    {
        $result = $this->where('challenge_id', $id)
                        ->get();

        $challenge = [
            'challenge_id' => $result[0]->challenge_id,
            'time' => $result[0]->time,
            'bet' => $result[0]->bet,
            'challenger_result_id' => $result[0]->challenger_result_id,
            'challenger_id' => $result[0]->challenger_id,
            'challenger_name' => $result[0]->challenger_name,
            'challenger_score' => $result[0]->challenger_score,
            'opponent_result_id' => $result[0]->opponent_result_id,
            'opponent_id' => $result[0]->opponent_id,
            'opponent_name' => $result[0]->opponent_name,
            'opponent_score' => $result[0]->opponent_score,
            'winner' => $result[0]->winner
        ];

        return $challenge;
    }

    /**
     * Get all challenges for one player from the v_challenges view
     *
     * @param int $userId
     * @property array $result
     * @property array $finishedChallenges
     * @property array $row
     * @return array $finishedChallenges
     */
    public function getFinishedChallenges($userId)
    {
        $result = $this->where('challenger_id', $userId)
                        ->whereNotNull('opponent_score')
                        ->orWhere('opponent_id', $userId)
                        ->whereNotNull('opponent_score')
                        ->get();

        $finishedChallenges = [];

        foreach ($result as $row) {
            array_push($finishedChallenges, [
                'challenge_id' => $row->challenge_id,
                'time' => $row->time,
                'bet' => $row->bet,
                'challenger_result_id' => $row->challenger_result_id,
                'challenger_id' => $row->challenger_id,
                'challenger_name' => $row->challenger_name,
                'challenger_score' => $row->challenger_score,
                'opponent_result_id' => $row->opponent_result_id,
                'opponent_id' => $row->opponent_id,
                'opponent_name' => $row->opponent_name,
                'opponent_score' => $row->opponent_score,
                'winner' => $row->winner
            ]);
        }

        return $finishedChallenges;
    }
}
