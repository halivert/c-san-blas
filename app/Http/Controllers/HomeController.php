<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Revolution\Google\Sheets\Facades\Sheets;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except([
            'index', 'login'
        ]);

        $this->middleware('guest')->only([
            'index', 'login'
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        Auth::attempt($request->only('password'));

        return redirect()->back()->withErrors([
            'password' => __('validation.wrong_credentials')
        ]);
    }

    public function markedHouses()
    {
        $user = Auth::user();
        $view = 'marked-houses';
        if (!$user or !$user->hasPermission($view))
            return abort(403);

        $markedHouses = Sheets::spreadsheet(env('MARKED_HOUSES_ID'))
            ->sheet('No%20tocar!A3:D120')
            ->all();

        return view($view, compact([
            'markedHouses'
        ]));
    }

    public function exhibitors()
    {
        $user = Auth::user();
        $view = 'exhibitors';
        if (!$user or !$user->hasPermission($view))
            return abort(403);

        $publishers = Sheets::spreadsheet(env('EXHIBITORS_DAYS_ID'))
            ->sheet('Publicadores!A3%3AN129')
            ->all();

        return view($view, compact([
            'publishers'
        ]));
    }
}
