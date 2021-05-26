<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property int  $id
* @property int  $user_id
* @property string  $date_played
* @property int  $result_1
* @property int  $result_2
* @property int  $result_3
* @property int  $result_4
* @property int  $result_5
* @property int  $result_6
* @property int  $result_one_pair
* @property int  $result_two_pairs
* @property int  $result_three
* @property int  $result_four
* @property int  $result_small_straight
* @property int  $result_large_straight
* @property int  $result_full_house
* @property int  $result_chance
* @property int  $result_yatzy
* @property int  $result_bonus
* @method orderByDesc(string $string1)
* @method where(mixed $columnOrArray = '', mixed $columnValue = '')
*/
class TableResult extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'results';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the latest result from results table
     *
     * @property object  $result
     * @return object
     */
    public function getLatestResult(): object
    {
        $result = $this->orderByDesc('id')
                                ->limit(1)
                                ->get();
        return $result[0];
    }

    /**
     * Save new result in results table
     *
     * @param  int  $user_id
     * @param  int  $result_bonus
     * @param  int  $result_1
     * @param  int  $result_2
     * @param  int  $result_3
     * @param  int  $result_4
     * @param  int  $result_5
     * @param  int  $result_6
     * @param  int  $result_one_pair
     * @param  int  $result_two_pairs
     * @param  int  $result_three
     * @param  int  $result_four
     * @param  int  $result_small_straight
     * @param  int  $result_large_straight
     * @param  int  $result_full_house
     * @param  int  $result_chance
     * @param  int  $result_yatzy
     * @return bool
     */
    public function saveResult(
        int $user_id,
        int $result_bonus,
        int $result_1,
        int $result_2,
        int $result_3,
        int $result_4,
        int $result_5,
        int $result_6,
        int $result_one_pair,
        int $result_two_pairs,
        int $result_three,
        int $result_four,
        int $result_small_straight,
        int $result_large_straight,
        int $result_full_house,
        int $result_chance,
        int $result_yatzy
    ) {
        $this->user_id = $user_id;
        $this->result_bonus = $result_bonus;
        $this->result_1 = $result_1;
        $this->result_2 = $result_2;
        $this->result_3 = $result_3;
        $this->result_4 = $result_4;
        $this->result_5 = $result_5;
        $this->result_6 = $result_6;
        $this->result_one_pair = $result_one_pair;
        $this->result_two_pairs = $result_two_pairs;
        $this->result_three = $result_three;
        $this->result_four = $result_four;
        $this->result_small_straight = $result_small_straight;
        $this->result_large_straight = $result_large_straight;
        $this->result_full_house = $result_full_house;
        $this->result_chance = $result_chance;
        $this->result_yatzy = $result_yatzy;

        return $this->save();
    }
}
