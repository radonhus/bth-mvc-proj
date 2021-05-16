<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property string $id
* @property string $time
* @property string $bet
* @property string $challenger_user_id
* @property string $challenger_result_id
* @property string $opponent_user_id
* @property string $opponent_result_id
*/
class ChallengesTable extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'challenges';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all open challenges where user has been challenged
     *
     * @param string $id
     * @property object $result
     * @property array $openChallenges
     * @return array $openChallenges
     */
    public function getOpenChallenges($id): array
    {
        $result = $this->where(['opponent_user_id' => $id, 'opponent_result_id' => null])
                                ->get();

        $openChallenges = [];

        foreach ($result as $row) {
            array_push($openChallenges, [
                'id' => $row->id,
                'time' => $row->time,
                'bet' => $row->bet,
                'challenger_user_id' => $row->challenger_user_id
            ]);
        }

        return $openChallenges;
    }

    /**
     * Get challenger resultId
     *
     * @param int $id
     * @property array $challenge
     * @return string $resultId
     */
    public function getChallengerResultId(int $id): string
    {
        $challenge = $this->where('id', $id)
                                ->get();

        $resultId = $challenge[0]->challenger_result_id;

        return $resultId;
    }

    /**
     * Update coins for one user
     *
     * @param int $id
     * @param int $totalBet
     * @property string $opponent_result_id
     * @property array $user
     */
    public function updateChallenge(int $id, int $totalBet, string $opponent_result_id): string
    {
        $updatedRows = $this->where('id', $id)
                                ->update(['bet' => $totalBet,
                                'opponent_result_id' => $opponent_result_id]);
        return $updatedRows;
    }

    /**
     * Save new challenge in challenges table
     *
     * @param string $challenger_user_id
     * @param int $challenger_result_id
     * @param string $opponent_user_id
     * @param string $bet
     * @return bool
     */
    public function saveNewChallenge(
        string $challenger_user_id, int $challenger_result_id,
        string $opponent_user_id, string $bet
        )
    {
        $this->challenger_user_id = $challenger_user_id;
        $this->challenger_result_id = $challenger_result_id;
        $this->opponent_user_id = $opponent_user_id;
        $this->bet = $bet;

        return $this->save();
    }

}
