<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegistrationController extends Controller
{

    /**
     * Return view to create new user.
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('userregister', [
            'title' => "Register | YatzyBonanza"
        ]);
    }

    /**
     * Authenticate input, save new user to db, redirect.
     * @return \Illuminate\Contracts\View\View
     */
    public function store()
    {
        $userObject = new User;

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

        return view('useraccount', [
            'title' => "My Account | YatzyBonanza"
        ]);
    }

}
