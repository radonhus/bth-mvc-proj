<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property int $id
* @property string $time
* @property int $bet
* @property int $challenger_user_id
* @property int $challenger_result_id
* @property int $opponent_user_id
* @property int $opponent_result_id
* @property string $denied
* @method where(mixed $columnOrArray = '', mixed $columnValue = '')
*/
class TableChallenges extends Model
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
     * Insert opponent result and total bet in challenge row
     *
     * @param int $challengeId
     * @param int $totalBet
     * @param int $resultId
     * @property string $updatedRows
     * @return string
     */
    public function updateChallenge(int $challengeId, int $totalBet, int $resultId): string
    {
        $updatedRows = $this->where('id', $challengeId)
                                ->update(['bet' => $totalBet, 'opponent_result_id' => $resultId]);
        return $updatedRows;
    }

    /**
     * Mark challenge as denied
     *
     * @param int $challengeId
     * @property string $updatedRows
     * @return string
     */
    public function denyChallenge(int $challengeId): string
    {
        $updatedRows = $this->where('id', $challengeId)
                                ->update(['denied' => 'denied']);
        return $updatedRows;
    }

    /**
     * Save new challenge in challenges table
     *
     * @param int $challenger_user_id
     * @param int $challenger_result_id
     * @param int $opponent_user_id
     * @param int $bet
     * @return bool
     */
    public function saveNewChallenge(
        int $challenger_user_id,
        int $challenger_result_id,
        int $opponent_user_id,
        int $bet
    ) {
        $this->challenger_user_id = $challenger_user_id;
        $this->challenger_result_id = $challenger_result_id;
        $this->opponent_user_id = $opponent_user_id;
        $this->bet = $bet;

        return $this->save();
    }
}
