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
class Highscore extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all highscores from highscores table in database.
     *
     * @property object  $highscoresDBObject
     * @property array $highscoresArray
     * @property int $rank
     * @property array $highscore
     * @return array $highscoresArray
     */
    public function getAllHighscores()
    {

        $highscoresDBObject = $this->orderByDesc('score')
                                ->limit(10)
                                ->get();

        $highscoresArray = [];
        $rank = 0;

        foreach ($highscoresDBObject as $highscore) {
            $rank += 1;
            array_push($highscoresArray, [
                'rank' => $rank,
                'player' => $highscore->player,
                'score' => $highscore->score,
                'date_played' => substr($highscore->date_played, 0, 10)
            ]);
        }

        return $highscoresArray;
    }

    /**
     * Save new result in highscore table
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
