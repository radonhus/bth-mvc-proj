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
* @property int $result_id
* @property int $dice_1
* @property int $dice_2
* @property int $dice_3
* @property int $dice_4
* @property int $dice_5
* @property int $dice_6
* @method orderByDesc(string $string1)
* @method where(mixed $columnOrArray = '', mixed $columnValue = '')
*/
class TableHistogram extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'histograms';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Save histogram from one game in histogram table
     *
     * @param  int  $resultId
     * @param  int  $dice1
     * @param  int  $dice2
     * @param  int  $dice3
     * @param  int  $dice4
     * @param  int  $dice5
     * @param  int  $dice6
     * @return bool
     */
    public function saveHistogram(
        int $resultId,
        int $dice1,
        int $dice2,
        int $dice3,
        int $dice4,
        int $dice5,
        int $dice6
    ) {
        $this->result_id = $resultId;
        $this->dice_1 = $dice1;
        $this->dice_2 = $dice2;
        $this->dice_3 = $dice3;
        $this->dice_4 = $dice4;
        $this->dice_5 = $dice5;
        $this->dice_6 = $dice6;

        return $this->save();
    }
}
