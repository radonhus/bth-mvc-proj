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
     * Get stats for one user
     *
     * @param string $userId
     * @property object $result
     * @property int $countGames
     * @property int $sumScore
     * @property int $avgScore
     * @property int $maxScore
     * @property int $countBonus
     * @property int $countOver250
     * @property int $quotaOver250
     * @property int $quotaBonus
     * @return array $allGames
     */
    public function getStatsUser($userId): array
    {

        $result = $this->where('user_id', $userId)
        ->orderByDesc('score')
        ->get();

        $sumScore = 0;
        $countGames = 0;
        $countBonus = 0;
        $avgScore = 0;
        $countOver250 = 0;
        $maxScore = intval($result[0]->score);

        foreach ($result as $row) {
            $countGames += 1;
            $sumScore += intval($row->score);
            if ($row->bonus == "50") { $countBonus += 1; }
            if ($row->score >= "250") { $countOver250 += 1; }
        }

        $avgScore = round($sumScore / $countGames);
        $quotaOver250 = round(($countOver250 / $countGames)*100);
        $quotaBonus = round(($countBonus / $countGames)*100);

        $stats = [
            'countGames' => $countGames,
            'sumScore' => $sumScore,
            'avgScore' => $avgScore,
            'maxScore' => $maxScore,
            'countBonus' => $countBonus,
            'countOver250' => $countOver250,
            'quotaOver250' => $quotaOver250,
            'quotaBonus' => $quotaBonus
        ];

        return $stats;
    }

    /**
     * Get basics for all games for one user
     *
     * @param string $userId
     * @property object $result
     * @property array $allGames
     * @return array $allGames
     */
    public function getAllGamesUser($userId): array
    {

        $result = $this->where('user_id', $userId)
        ->orderByDesc('score')
        ->get();

        $allGames = [];

        foreach ($result as $row) {
            array_push($allGames, [
                'result_id' => $row->result_id,
                'bonus' => $row->bonus,
                'date_played' => substr($row->date_played, 0, 10),
                'score' => $row->score
            ]);
        }

        return $allGames;
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
