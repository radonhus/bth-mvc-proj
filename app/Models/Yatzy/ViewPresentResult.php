<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
*/
class ViewPresentResult extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'v_presentresult';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get basics for one game
     *
     * @param string $result_id
     * @property object $result
     * @property array $histogram
     * @return array $histogram
     */
    public function getResult($result_id): array
    {

        $result = $this->where('result_id', $result_id)
                                ->get();

        $resultBasics = [
                'result_id' => $result[0]->result_id,
                'user_id' => $result[0]->user_id,
                'name' => $result[0]->name,
                'bonus' => $result[0]->bonus,
                'date_played' => $result[0]->date_played,
                'score' => $result[0]->score
            ];

        return $resultBasics;
    }

    /**
     * Get scorecard for one game
     *
     * @param string $result_id
     * @property object $result
     * @property array $scorecard
     * @return array $scorecard
     */
    public function getScorecard($result_id): array
    {

        $result = $this->where('result_id', $result_id)
                                ->get();

        $scorecard = [
                '1' => $result[0]->scorecard_1,
                '2' => $result[0]->scorecard_2,
                '3' => $result[0]->scorecard_3,
                '4' => $result[0]->scorecard_4,
                '5' => $result[0]->scorecard_5,
                '6' => $result[0]->scorecard_6,
                'one_pair' => $result[0]->scorecard_one_pair,
                'two_pairs' => $result[0]->scorecard_two_pairs,
                'three' => $result[0]->scorecard_three,
                'four' => $result[0]->scorecard_four,
                'small_straight' => $result[0]->scorecard_small_straight,
                'large_straight' => $result[0]->scorecard_large_straight,
                'full_house' => $result[0]->scorecard_full_house,
                'chance' => $result[0]->scorecard_chance,
                'yatzy' => $result[0]->scorecard_yatzy
            ];

        return $scorecard;
    }

    /**
     * Get histogram for one game
     *
     * @param string $result_id
     * @property object $result
     * @property array $histogram
     * @return array $histogram
     */
    public function getHistogram($result_id): array
    {

        $result = $this->where('result_id', $result_id)
                                ->get();

        $histogram = [
                '1' => $result[0]->histogram_1,
                '2' => $result[0]->histogram_2,
                '3' => $result[0]->histogram_3,
                '4' => $result[0]->histogram_4,
                '5' => $result[0]->histogram_5,
                '6' => $result[0]->histogram_6
            ];

        return $histogram;
    }
}
