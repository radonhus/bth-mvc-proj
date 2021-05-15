<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Yatzy\Yatzy;
use App\Models\Yatzy\ResultTable;
use App\Models\Yatzy\HistogramTable;
use Illuminate\Http\Request;

class YatzyController extends Controller
{
    /**
     * Start a new Yatzy session.
     *
     * @property object  $yatzyObject
     * @property array  $data
     * @return \Illuminate\Contracts\View\View
     */
    public function start()
    {
        $yatzyObject = new Yatzy();
        $data = $yatzyObject->startNewRound();
        session()->put('yatzy', $yatzyObject);

        return view('yatzy', [
            'title' => "Yatzy | YatzyBonanza",
            'data' => $data
        ]);
    }

    /**
     * Play Yatzy using request data from HTML form.
     *
     * @param  Request  $request
     * @property  array  $post
     * @property  array  $request
     * @property  object  $yatzyObject
     * @property  array  $data
     * @return \Illuminate\Contracts\View\View
     */
    public function play(Request $request)
    {

        $post = $request->all();
        $yatzyObject = session()->get('yatzy');
        $data = $yatzyObject->play($post);
        session()->put('yatzy', $yatzyObject);

        return view('yatzy', [
            'title' => "Yatzy | YatzyBonanza",
            'data' => $data
        ]);
    }

    /**
     * Present top ten results
     *
     * @property object  $result
     * @property array  $results
     * @return \Illuminate\Contracts\View\View
     */
    public function highScores()
    {
        $result = new ResultTable();
        $topTenArray = $result->getAllResults();

        return view('yatzyhighscores', [
            'title' => "Yatzy | YatzyBonanza",
            'highscores' => $topTenArray
        ]);
    }

    /**
     * Save submitted result in result table and histogram table.
     * Call highScores() to present highscores
     *
     * @param  Request  $request
     * @property Request  $request
     * @property object  $result
     * @property int $result_id
     * @property object  $view
     * @return \Illuminate\Contracts\View\View
     */
    public function submitResult(Request $request)
    {
        $result = new ResultTable();
        $histogram = new HistogramTable();

        if ($request) {
            $result->saveResult(
                request('user_id'), request('score'), request('bonus'),
                request('1'), request('2'), request('3'), request('4'),
                request('5'), request('6'), request('one_pair'), request('two_pairs'),
                request('three'), request('four'), request('small_straight'),
                request('large_straight'), request('full_house'), request('chance'),
                request('yatzy')
            );

            $result_id = $result->getLatestResult()->id;

            $histogram->saveHistogram(
                $result_id,
                request('dice_1'), request('dice_2'),
                request('dice_3'), request('dice_4'),
                request('dice_5'), request('dice_6')
            );
        }

        $view = $this->highScores();

        return $view;
    }
}
