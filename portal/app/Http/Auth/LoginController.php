<?php

namespace App\Http\Auth;

use App\Models\curstats;
use App\Models\experience;
use App\Models\players;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Http\Request;
use App\Http\Controller;
use function App\Helpers\passwd_compat_hasher;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function show_login_form(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('secure.login');
    }

    public function process_login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'username' => 'required|min:3|max:12|unique:cabbage.players',
            'email' => 'required|min:6|max:255',
            'password' => 'required|min:4|max:20',
        ]);

        $credentials = $request->except(['_token']);

        $user = players::where('username', $request->username)->first();

        if (!$user) {
            session()->flash('message', 'Invalid credentials');
            return redirect()->back();
        }

        $form_pass = $request['password'];
        if ($user->salt) {
            // accounts with old password compatibility
            $form_pass = passwd_compat_hasher($form_pass, $user->salt);
        }

        if (auth()->attempt(['username' => $request['username'], 'password' => $form_pass])) {

            return redirect()->route('home');

        } else {
            session()->flash('message', 'Invalid credentials');
            return redirect()->back();
        }
    }

    public function show_signup_form(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('secure.Choose_a_username');
    }

    public function process_signup(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'username' => 'required|min:3|max:12|unique:cabbage.players',
            'email' => 'required|min:6|max:255',
            'password' => 'required|min:4|max:20',
        ]);

        $user = players::where('username', $request->username)->first();

        if ($user) {
            session()->flash('message', 'Username already taken!');
            return redirect()->back();
        }

        $user = players::create([
            'username' => trim($request->input('username')),
            'email' => strtolower($request->input('email')),
            'pass' => bcrypt($request->input('password')),
        ]);

        curstats::create([
            'playerID' => $user->id,
            'hits' => 10
        ]);

        experience::create([
            'playerID' => $user->id,
            'hits' => 4000
        ]);

        session()->flash('message', 'Your account is created');

        return redirect()->route('login');
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function username(): string
    {
        return 'username';
    }
}