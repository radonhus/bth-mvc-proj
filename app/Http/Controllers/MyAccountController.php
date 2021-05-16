<?php

namespace App\Http\Controllers;

use App\Models\Yatzy\ChallengesTable;
use App\Models\User;
use Illuminate\Http\Request;

class MyAccountController extends Controller
{

    /**
     * Return view for My Account.
     * @return \Illuminate\Contracts\View\View
     */
    public function myAccount()
    {
        $challenges = new ChallengesTable();
        $users = new User();

        $openChallenges = $challenges->getOpenChallenges(auth()->user()->id);
        $userCoins = $users->getCoins(auth()->user()->id);

        return view('useraccount', [
            'title' => "My Account | YatzyBonanza",
            'openChallenges' => $openChallenges,
            'userCoins' => $userCoins
        ]);
    }
}
