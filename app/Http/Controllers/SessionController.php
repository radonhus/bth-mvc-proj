<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Return view to login user.
     * @property  object  $myAccount
     * @return \Illuminate\Contracts\View\View
     */
    public function start()
    {
        $myAccount = new AccountController();

        // If user is logged in, return myAccount view
        if (auth()->check()) {
            return $myAccount->myAccount();
        }

        return view('start', [
            'title' => "Home | YatzyBonanza"
        ]);
    }

    /**
     * Verify login details and log user in or return error message.
     * @return \Illuminate\Contracts\View\View
     */
    public function verifyCreate()
    {
        if (auth()->attempt(request(['name', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'Your username or password is incorrect, please try again'
            ]);
        }

        $myAccount = new AccountController();

        return $myAccount->myAccount();
    }

    /**
     * Logout user and return start view
     * @return \Illuminate\Contracts\View\View
     */
    public function logoutDestroy()
    {
        auth()->logout();

        return view('start', [
            'title' => "Home | YatzyBonanza"
        ]);
    }
}
