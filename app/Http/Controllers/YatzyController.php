<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Yatzy\Yatzy;
use App\Models\User;
use Illuminate\Http\Request;

class YatzyController extends Controller
{

    /**
     * Generate gamemode menu view
     *
     * @property object  $usersObject
     * @property array  $users
     * @return \Illuminate\Contracts\View\View
     */
    public function gamemode()
    {
        $usersObject = new User();

        $users = $usersObject->getAllUsers();
        $coins = $usersObject->getCoins(auth()->user()->id);

        return view('gamemode', [
            'title' => "Choose mode | ¥atzyBonanza",
            'users' => $users,
            'coinsCurrentUser' => $coins
        ]);
    }

    /**
     * Start a new Yatzy session.
     *
     * @param Request $request
     * @property array $post
     * @property string $mode
     * @property string $bet
     * @property string $opponent
     * @property string $challengeId
     * @property object $yatzyObject
     * @property array $data
     * @return \Illuminate\Contracts\View\View
     */
    public function start(Request $request)
    {
        $post = $request->all();
        $mode = $post['mode'];
        $bet = $post['bet'];
        $opponent = $post['opponent'];
        $opponentName = "";
        $challengeId = $post['challengeId'];

        if ($opponent != "0") {
            $usersObject = new User();
            $opponentName = $usersObject->getName($opponent);
        }

        $yatzyObject = new Yatzy(
            $mode,
            $bet,
            $opponent,
            $opponentName,
            $challengeId
        );

        $data = $yatzyObject->startNewRound();
        session()->put('yatzy', $yatzyObject);

        return view('yatzy', [
            'title' => "Yatzy | ¥atzyBonanza",
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
            'title' => "Yatzy | ¥atzyBonanza",
            'data' => $data
        ]);
    }
}
