<?php

namespace App\Models\Yatzy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
* The following properties are columns in the table that
* the model represents (to make phpstan happy)
*
* @property string $player
* @property string $score
* @method orderByDesc(string $string1)
* @method where(string $string1, string $string2)
*/
class Result extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all Results from results table in database.
     *
     * @property object  $resultsDBObject
     * @property array $topTenArray
     * @property int $rank
     * @property array $result
     * @return array $topTenArray
     */
    public function getAllResults()
    {

        $resultsDBObject = $this->orderByDesc('score')
                                ->limit(10)
                                ->get();

        $topTenArray = [];
        $rank = 0;

        foreach ($resultsDBObject as $result) {
            $rank += 1;
            array_push($topTenArray, [
                'rank' => $rank,
                'player' => $result->player,
                'score' => $result->score,
                'date_played' => substr($result->date_played, 0, 10)
            ]);
        }

        return $topTenArray;
    }

    /**
     * Save new result in results table
     *
     * @param  string  $player
     * @param  string  $score
     * @return bool
     */
    public function saveResult(string $player, string $score)
    {
        $this->player = $player;
        $this->score = $score;

        return $this->save();
    }
}
