<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property string $result_id
* @property string $user_id
* @property string $name
* @property string $score
* @property string $bonus
* @property string $date_played
*/
class ViewHighscores extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_highscores';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all highscores from the v_highscores view
     *
     * @property object $result
     * @property int $topScore
     * @property array $topTenArray
     * @property int $rank
     * @property array $result
     * @return array $topTenArray
     */
    public function getHighscores()
    {

        $result = $this->orderByDesc('score')
                                ->limit(10)
                                ->get();

        $topScore = intval($result[0]['score']);

        $topTenArray = [];
        $rank = 0;

        foreach ($result as $row) {
            $percent = round((intval($row->score) / $topScore)*100);
            $rank += 1;
            array_push($topTenArray, [
                'rank' => $rank,
                'result_id' => $row->result_id,
                'user_id' => $row->user_id,
                'name' => $row->name,
                'score' => $row->score,
                'percent' => $percent,
                'bonus' => $row->bonus,
                'date_played' => substr($row->date_played, 0, 10)
            ]);
        }

        return $topTenArray;
    }
}
