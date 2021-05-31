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
     * @param  array $post

     * @return bool
     */
    public function saveResult(array $post)
    {
        $this->user_id = $post['user_id'];
        $this->result_bonus = $post['bonus'];
        $this->result_1 = $post['1'];
        $this->result_2 = $post['2'];
        $this->result_3 = $post['3'];
        $this->result_4 = $post['4'];
        $this->result_5 = $post['5'];
        $this->result_6 = $post['6'];
        $this->result_one_pair = $post['one_pair'];
        $this->result_two_pairs = $post['two_pairs'];
        $this->result_three = $post['three'];
        $this->result_four = $post['four'];
        $this->result_small_straight = $post['small_straight'];
        $this->result_large_straight = $post['large_straight'];
        $this->result_full_house = $post['full_house'];
        $this->result_chance = $post['chance'];
        $this->result_yatzy = $post['yatzy'];

        return $this->save();
    }
}
