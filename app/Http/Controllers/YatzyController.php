<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Yatzy\Yatzy;
use App\Models\Yatzy\Result;
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
     * @property object  $resultsDBObject
     * @property array  $results
     * @return \Illuminate\Contracts\View\View
     */
    public function highScores()
    {
        $resultsDBObject = new Result();
        $topTenArray = $resultsDBObject->getAllResults();

        return view('yatzyhighscores', [
            'title' => "Yatzy | YatzyBonanza",
            'highscores' => $topTenArray
        ]);
    }

    /**
     * Save submitted score and call highScores() to present highscores
     *
     * @param  Request  $request
     * @property Request  $request
     * @property object  $newScore
     * @property object  $view
     * @return \Illuminate\Contracts\View\View
     */
    public function submitResult(Request $request)
    {
        $newScore = new Result();

        if ($request and is_string(request('player')) and is_string(request('score'))) {
            $newScore->saveResult(
                request('player'),
                request('score')
            );
        }

        $view = $this->highScores();

        return $view;
    }
}
