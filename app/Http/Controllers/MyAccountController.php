<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyAccountController extends Controller
{

    /**
     * Return view for My Account.
     * @return \Illuminate\Contracts\View\View
     */
    public function start()
    {

        return view('useraccount', [
            'title' => "My Account | YatzyBonanza"
        ]);
    }
}
