<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Database\TableChallenges;
use App\Models\Database\ViewChallenges;
use App\Models\Database\ViewResults;
use Illuminate\Http\Request;
use App\Models\Database\TableUsers;

class AccountController extends Controller
{

    /**
     * Return (start)view to create a new user.
     * @return Object
     */
    public function start()
    {
        return view('userregister', [
            'title' => "Register | ¥atzyBonanza"
        ]);
    }

    /**
     * Authenticate name and password, save new user to db, redirect to myAccount.
     * @return Object
     */
    public function verifySave()
    {
        $userObject = new TableUsers();

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

        return redirect()->route('myaccount');
    }

    /**
     * Mark challenge as denied, return the myAccount view
     * @param Request $request
     * @property array $post
     * @property string $challengeId
     * @return Object
     */
    public function denyChallenge(Request $request)
    {
        $post = $request->all();
        $challengeId = intval($post['challengeId']);
        $challengerId = intval($post['challenger']);
        $bet = intval($post['bet']);

        $challenges = new TableChallenges();
        $users = new TableUsers();

        $challenges->denyChallenge($challengeId);
        $users->updateBalance($challengerId, $bet);

        return redirect()->route('myaccount');
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
     * @return Object
     */
    public function myAccount()
    {
        $viewChallenges = new ViewChallenges();
        $results = new ViewResults();
        $users = new TableUsers();

        $userId = auth()->user()->id;

        $finishedChallenges = $viewChallenges->getFinishedChallenges($userId);
        $openChallenges = $viewChallenges->getOpenChallenges($userId);
        $openChallengesSent = $viewChallenges->getOpenChallengesSent($userId);
        $allGames = $results->getAllGamesUser(intval($userId));
        $stats = $results->getStatsUser($userId);
        $userCoins = $users->getCoins($userId);

        return view('useraccount', [
            'title' => "My Account | ¥atzyBonanza",
            'finishedChallenges' => $finishedChallenges,
            'openChallenges' => $openChallenges,
            'openChallengesSent' => $openChallengesSent,
            'allGames' => $allGames,
            'stats' => $stats,
            'userCoins' => $userCoins
        ]);
    }
}
