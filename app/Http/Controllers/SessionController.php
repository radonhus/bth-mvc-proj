<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Return view to login user.
     * @property  object  $myAccount
     * @return Object
     */
    public function start()
    {
        $myAccount = new AccountController();

        // If user is logged in, return myAccount view
        if (auth()->check()) {
            return $myAccount->myAccount();
        }

        return view('start', [
            'title' => "Home | ¥atzyBonanza"
        ]);
    }

    /**
     * Verify login details and log user in or return error message.
     * @return Object
     */
    public function verifyCreate()
    {
        if (auth()->attempt(request(['name', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'Your username or password is incorrect, please try again'
            ]);
        }

        return redirect()->route('myaccount');
    }

    /**
     * Logout user and return start view
     * @return Object
     */
    public function logoutDestroy()
    {
        auth()->logout();

        return view('start', [
            'title' => "Home | ¥atzyBonanza"
        ]);
    }
}
