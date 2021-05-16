<?php

namespace App\Http\Controllers;

use App\Models\Yatzy\ChallengesTable;
use App\Models\Yatzy\ViewChallenges;
use App\Models\User;
use Illuminate\Http\Request;

class MyAccountController extends Controller
{

    /**
     * Return view for My Account.
     * @property int $userId
     * @return \Illuminate\Contracts\View\View
     */
    public function myAccount()
    {
        $viewChallenges = new ViewChallenges();
        $users = new User();

        $userId = auth()->user()->id;

        $finishedChallenges = $viewChallenges->getFinishedChallenges($userId);
        $openChallenges = $viewChallenges->getOpenChallenges($userId);
        $openChallengesSent = $viewChallenges->getOpenChallengesSent($userId);
        $userCoins = $users->getCoins($userId);

        return view('useraccount', [
            'title' => "My Account | YatzyBonanza",
            'finishedChallenges' => $finishedChallenges,
            'openChallenges' => $openChallenges,
            'openChallengesSent' => $openChallengesSent,
            'userCoins' => $userCoins
        ]);
    }
}
