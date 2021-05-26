<?php

namespace App\Http\Controllers;

use App\Models\Database\TableChallenges;
use App\Models\Database\ViewChallenges;
use App\Models\Database\ViewResults;
use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{

    /**
     * Return view to create new user.
     * @return \Illuminate\Contracts\View\View
     */
    public function start()
    {
        return view('userregister', [
            'title' => "Register | YatzyBonanza"
        ]);
    }

    /**
     * Authenticate input, save new user to db, redirect.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifySave()
    {
        $userObject = new User();

        if ($userObject->checkUserNameTaken(request('name'))) {
            return back()->withErrors([
                'message' => 'The username is already taken, please try again'
            ]);
        }

        $this->validate(request(), [
            'name' => 'required',
            'password' => 'required|confirmed'
        ]);

        $user = $userObject->create(request(['name', 'password']));

        auth()->login($user);

        return $this->myAccount();
    }

    /**
     * Mark challenge as denied, return myAccount view
     * @param Request $request
     * @property array $post
     * @property string $challengeId
     * @return \Illuminate\Contracts\View\View
     */
    public function denyChallenge(Request $request)
    {
        $post = $request->all();
        $challengeId = $post['challengeId'];
        $challengerId = $post['challenger'];
        $bet = $post['bet'];

        $challenges = new TableChallenges();
        $users = new User();

        $challenges->denyChallenge($challengeId);
        $users->updateBalance($challengerId, $bet);

        return $this->myAccount();
    }

    /**
     * Return view for My Account.
     * @property object $viewChallenges
     * @property object $results
     * @property object $users
     * @property string $userId
     * @property array $finishedChallenges
     * @property array $openChallenges
     * @property array $openChallengesSent
     * @property array $allGames
     * @property string $userCoins
     * @return \Illuminate\Contracts\View\View
     */
    public function myAccount()
    {
        $viewChallenges = new ViewChallenges();
        $results = new ViewResults();
        $users = new User();

        $userId = auth()->user()->id;

        $finishedChallenges = $viewChallenges->getFinishedChallenges($userId);
        $openChallenges = $viewChallenges->getOpenChallenges($userId);
        $openChallengesSent = $viewChallenges->getOpenChallengesSent($userId);
        $allGames = $results->getAllGamesUser($userId);
        $stats = $results->getStatsUser($userId);
        $userCoins = $users->getCoins($userId);

        return view('useraccount', [
            'title' => "My Account | YatzyBonanza",
            'finishedChallenges' => $finishedChallenges,
            'openChallenges' => $openChallenges,
            'openChallengesSent' => $openChallengesSent,
            'allGames' => $allGames,
            'stats' => $stats,
            'userCoins' => $userCoins
        ]);
    }
}
