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
     * @param  int  $userId
     * @param  int  $resultBonus
     * @param  int  $result1
     * @param  int  $result2
     * @param  int  $result3
     * @param  int  $result4
     * @param  int  $result5
     * @param  int  $result6
     * @param  int  $resultOnePair
     * @param  int  $resultTwoPairs
     * @param  int  $resultThree
     * @param  int  $resultFour
     * @param  int  $resultSmallStraight
     * @param  int  $resultLargeStraight
     * @param  int  $resultFullHouse
     * @param  int  $resultChance
     * @param  int  $resultYatzy
     * @return bool
     */
    public function saveResult(
        int $userId,
        int $resultBonus,
        int $result1,
        int $result2,
        int $result3,
        int $result4,
        int $result5,
        int $result6,
        int $resultOnePair,
        int $resultTwoPairs,
        int $resultThree,
        int $resultFour,
        int $resultSmallStraight,
        int $resultLargeStraight,
        int $resultFullHouse,
        int $resultChance,
        int $resultYatzy
    ) {
        $this->user_id = $userId;
        $this->result_bonus = $resultBonus;
        $this->result_1 = $result1;
        $this->result_2 = $result2;
        $this->result_3 = $result3;
        $this->result_4 = $result4;
        $this->result_5 = $result5;
        $this->result_6 = $result6;
        $this->result_one_pair = $resultOnePair;
        $this->result_two_pairs = $resultTwoPairs;
        $this->result_three = $resultThree;
        $this->result_four = $resultFour;
        $this->result_small_straight = $resultSmallStraight;
        $this->result_large_straight = $resultLargeStraight;
        $this->result_full_house = $resultFullHouse;
        $this->result_chance = $resultChance;
        $this->result_yatzy = $resultYatzy;

        return $this->save();
    }
}
