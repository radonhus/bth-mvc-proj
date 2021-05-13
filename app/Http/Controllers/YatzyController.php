<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Yatzy\Yatzy;
use App\Models\Yatzy\Highscore;
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
            'title' => "Yatzy | DiceLaVerdad",
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
            'title' => "Yatzy | DiceLaVerdad",
            'data' => $data
        ]);
    }

    /**
     * Present highscores
     *
     * @property object  $highscoreObject
     * @property array  $highscores
     * @return \Illuminate\Contracts\View\View
     */
    public function highScores()
    {
        $highscoreObject = new Highscore();
        $highscores = $highscoreObject->getAllHighscores();

        return view('yatzyhighscores', [
            'title' => "Yatzy | DiceLaVerdad",
            'highscores' => $highscores
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
    public function submitHighScore(Request $request)
    {
        $newScore = new Highscore();

        if ($request and is_string(request('player')) and is_string(request('score'))) {
            $newScore->saveResult(request('player'), request('score'));
        }

        $view = $this->highScores();

        return $view;
    }
}
