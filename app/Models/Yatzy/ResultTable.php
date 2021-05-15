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
* @method orderByDesc(string $string1)
* @method where(string $string1, string $string2)
*/
class ResultTable extends Model
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
     * @param  string  $user_id
     * @param  string  $result_bonus
     * @param  string  $result_1
     * @param  string  $result_2
     * @param  string  $result_3
     * @param  string  $result_4
     * @param  string  $result_5
     * @param  string  $result_6
     * @param  string  $result_one_pair
     * @param  string  $result_two_pairs
     * @param  string  $result_three
     * @param  string  $result_four
     * @param  string  $result_small_straight
     * @param  string  $result_large_straight
     * @param  string  $result_full_house
     * @param  string  $result_chance
     * @param  string  $result_yatzy
     * @return bool
     */
    public function saveResult(
        string $user_id,
        string $result_bonus,
        string $result_1,
        string $result_2,
        string $result_3,
        string $result_4,
        string $result_5,
        string $result_6,
        string $result_one_pair,
        string $result_two_pairs,
        string $result_three,
        string $result_four,
        string $result_small_straight,
        string $result_large_straight,
        string $result_full_house,
        string $result_chance,
        string $result_yatzy
        )
    {
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
