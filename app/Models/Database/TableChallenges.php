<?php

declare(strict_types=1);

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property int $challenge_id
* @property string $time
* @property int $bet
* @property int $challenger_user_id
* @property int $challenger_result_id
* @property int $opponent_user_id
* @property int $opponent_result_id
* @property string $denied
* @method orderByDesc(mixed $columnOrArray = '', mixed $columnValue = '')
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
     * @param int $challengeId
     * @property array $challenge
     * @return string $resultId
     */
    public function getChallengerResultId(int $challengeId): string
    {
        $challenge = $this->where('id', $challengeId)
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
     * @property int $updatedRows
     * @return int
     */
    public function updateChallenge(int $challengeId, int $totalBet, int $resultId): int
    {
        $updatedRows = $this->where('id', $challengeId)
                                ->update(['bet' => $totalBet, 'opponent_result_id' => $resultId]);
        return $updatedRows;
    }

    /**
     * Mark challenge as denied
     *
     * @param int $challengeId
     * @property int $updatedRows
     * @return int
     */
    public function denyChallenge(int $challengeId): int
    {
        $updatedRows = $this->where('id', $challengeId)
                                ->update(['denied' => 'denied']);

        return $updatedRows;
    }

    /**
     * Save new challenge in challenges table
     *
     * @param int $challengerUserId
     * @param int $challengerResultId
     * @param int $opponentUserId
     * @param int $bet
     * @return bool
     */
    public function saveNewChallenge(
        int $challengerUserId,
        int $challengerResultId,
        int $opponentUserId,
        int $bet
    ) {
        $this->challenger_user_id = $challengerUserId;
        $this->challenger_result_id = $challengerResultId;
        $this->opponent_user_id = $opponentUserId;
        $this->bet = $bet;

        return $this->save();
    }
}
