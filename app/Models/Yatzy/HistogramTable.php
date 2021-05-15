<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property string $id
* @property string $result_id
* @property string $dice_1
* @property string $dice_2
* @property string $dice_3
* @property string $dice_4
* @property string $dice_5
* @property string $dice_6
* @method orderByDesc(string $string1)
* @method where(string $string1, string $string2)
*/
class HistogramTable extends Model
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
     * Get histogram for one game
     *
     * @param string  $result_id
     * @property object $result
     * @property array $histogram
     * @return array $histogram
     */
    public function getOneHistogram($result_id): array
    {

        $result = $this->where('result_id', $result_id)
                                ->get();

        $histogram = [
                'dice_1' => $result[0]->dice_1,
                'dice_2' => $result[0]->dice_2,
                'dice_3' => $result[0]->dice_3,
                'dice_4' => $result[0]->dice_4,
                'dice_5' => $result[0]->dice_5,
                'dice_6' => $result[0]->dice_6
            ];

        return $histogram;
    }

    /**
     * Save histogram from one game in histogram table
     *
     * @param  int  $result_id
     * @param  string  $dice_1
     * @param  string  $dice_2
     * @param  string  $dice_3
     * @param  string  $dice_4
     * @param  string  $dice_5
     * @param  string  $dice_6
     * @return bool
     */
    public function saveHistogram(
        int $result_id,
        string $dice_1, string $dice_2,
        string $dice_3, string $dice_4,
        string $dice_5, string $dice_6
        )
    {
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
