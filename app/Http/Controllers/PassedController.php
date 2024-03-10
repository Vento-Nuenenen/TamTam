<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PassedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Application|Factory|View
    {
        $participations = Participant::all();

        return view('passed.passed', ['participations' => $participations]);
    }

    public function set_flag(Request $request): RedirectResponse
    {
        ! empty($request->input('has_passed')) ? $passed = $request->input('has_passed') : $passed = [];
        ! empty($request->input('not_passed')) ? $not_passed = array_diff($request->input('not_passed'), $passed) : $not_passed = [];

        foreach ($passed as $pass) {
            Participant::where('id', '=', $pass)->update(['course_passed' => true]);
        }

        foreach ($not_passed as $npsd) {
            Participant::where('id', '=', $npsd)->update(['course_passed' => false]);
        }

        return redirect()->back()->with('message', 'Bestanden wurde aktualisiert');
    }
}
