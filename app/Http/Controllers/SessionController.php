<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Return view to login user.
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('userlogin', [
            'title' => "Login | YatzyBonanza"
        ]);
    }

    /**
     * Verify login details and log user in / return error message.
     * @return \Illuminate\Contracts\View\View
     */
    public function store()
    {
        if (auth()->attempt(request(['name', 'password'])) == false) {
            return back()->withErrors([
                'message' => 'Your username or password is incorrect, please try again'
            ]);
        }

        return view('useraccount', [
            'title' => "My account | YatzyBonanza"
        ]);
    }

    /**
     * Logout user and return start views.
     * @return \Illuminate\Contracts\View\View
     */
    public function destroy()
    {
        auth()->logout();

        return view('start', [
            'title' => "Home | YatzyBonanza"
        ]);
    }
}
