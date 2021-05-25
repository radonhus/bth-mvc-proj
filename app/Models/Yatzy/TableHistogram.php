<?php

namespace App\Models\Yatzy;

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
     * @param  int  $result_id
     * @param  int  $dice_1
     * @param  int  $dice_2
     * @param  int  $dice_3
     * @param  int  $dice_4
     * @param  int  $dice_5
     * @param  int  $dice_6
     * @return bool
     */
    public function saveHistogram(
        int $result_id,
        int $dice_1,
        int $dice_2,
        int $dice_3,
        int $dice_4,
        int $dice_5,
        int $dice_6
    ) {
        $this->result_id = $result_id;
        $this->dice_1 = $dice_1;
        $this->dice_2 = $dice_2;
        $this->dice_3 = $dice_3;
        $this->dice_4 = $dice_4;
        $this->dice_5 = $dice_5;
        $this->dice_6 = $dice_6;

        return $this->save();
    }
}
