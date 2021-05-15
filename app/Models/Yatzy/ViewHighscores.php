<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property string $user_id
* @property string $score
* @property string  $result_bonus
* @property string  $result_1
* @property string  $result_2
* @property string  $result_3
* @property string  $result_4
* @property string  $result_5
* @property string  $result_6
* @property string  $result_one_pair
* @property string  $result_two_pairs
* @property string  $result_three
* @property string  $result_four
* @property string  $result_small_straight
* @property string  $result_large_straight
* @property string  $result_full_house
* @property string  $result_chance
* @property string  $result_yatzy
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
     * Get all results from results table in database.
     *
     * @property object $result
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

        $topTenArray = [];
        $rank = 0;

        foreach ($result as $row) {
            $rank += 1;
            array_push($topTenArray, [
                'rank' => $rank,
                'result_id' => $row->result_id,
                'user_id' => $row->user_id,
                'name' => $row->name,
                'score' => $row->score,
                'bonus' => $row->bonus,
                'date_played' => substr($row->date_played, 0, 10)
            ]);
        }

        return $topTenArray;
    }
}
