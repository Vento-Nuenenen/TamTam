<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        if ($request->input('search') == null) {
            $users = User::all();
        } else {
            $search_string = $request->input('search');
            $users = User::where('scout_name', 'LIKE', "%$search_string%")
                ->orWhere('last_name', 'LIKE', "%$search_string%")
                ->orWhere('first_name', 'LIKE', "%$search_string%")->get();
        }

        return view('users.users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        return view('users.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $scout_name = $request->input('scout_name');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');

        $password = $request->input('password');
        $password_repeat = $request->input('password_repeat');

        if ($password != null && $password === $password_repeat) {
            $password = Hash::make($password);

            $password_repeat = null;

            User::create([
                'scout_name' => $scout_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
            ]);

            return redirect()->back()->with('message', 'Benutzer wurde erstellt.');
        } else {
            return redirect()->back()->with('error', 'Passwort wurde nicht korrekt wiederholt!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uid): View|Application|Factory
    {
        $user = User::find($uid);

        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uid): RedirectResponse
    {
        $scout_name = $request->input('scout_name');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');

        $password = $request->input('password');
        $password_repeat = $request->input('password_repeat');

        if ($password != null && $password === $password_repeat) {
            $password = Hash::make($password);

            $password_repeat = null;

            User::where('id', '=', $uid)->update([
                'scout_name' => $scout_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
            ]);

            return redirect()->back()->with('message', 'Benutzer wurde aktualisiert.');
        } elseif ($password == null) {
            User::where('id', '=', $uid)->update([
                'scout_name' => $scout_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
            ]);

            return redirect()->back()->with('message', 'Benutzer wurde aktualisiert. Das Passwort wurde beibehalten!');
        } else {
            return redirect()->back()->with('error', 'Passwort wurde nicht korrekt wiederholt!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uid)
    {
        User::destroy($uid);

        return redirect()->back()->with('message', 'Benutzer erfolgreich gelöscht.');
    }
}
