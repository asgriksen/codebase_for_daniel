<?php namespace App\Http\Controllers;

use Auth;

class IndexController extends Controller
{
    public function __construct( ) {
    }

    public function index()
    {
        // Check whether the user is logged in
        //
        if(!Auth::user()) {
            return redirect('login');
        }

        // Render web app
        //
        return view('index');
    }


    public function login() {
        // Check whether the user is logged in
        //
        if(Auth::user()) {
            return redirect('/');
        }

        // Render web app
        //
        return view('login');
    }

    public function logout() {
        Auth::logout();

        return redirect('login');
    }
}
