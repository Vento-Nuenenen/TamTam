<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Points;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PointTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        if ($request->search == null) {
            $transactions = Points::with('participants')
                ->select('points.*', 'participations.first_name', 'participations.last_name', 'participations.scout_name', 'participations.barcode')
                ->get();
        } else {
            $search_string = $request->input('search');
            $transactions = Points::with('participants')
                ->where('scout_name', 'LIKE', "%$search_string%")
                ->orWhere('last_name', 'LIKE', "%$search_string%")
                ->orWhere('first_name', 'LIKE', "%$search_string%")
                ->orWhere('barcode', 'LIKE', "%$search_string%")
                ->orWhere('reason', 'LIKE', "%$search_string%")
                ->orderBy('points.id', 'DESC')->get();
        }

        return view('transactions.transactions', ['transactions' => $transactions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $participants = Participant::all();

        return view('transactions.add', ['participants' => $participants]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $participant = $request->input('participant');
        $points = $request->input('points');
        $reason = $request->input('reason');

        $is_addition = ! empty($request->input('is_addition')) ? true : false;

        $response = Points::create([
            'reason' => $reason,
            'points' => $points,
            'is_addition' => $is_addition,
        ]);
        $response->participant($participant);

        return redirect()->back()->with('message', 'Transaktion wurde erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Points $points): View|Application|Factory
    {
        $point = Points::where('id', '=', $points)->first();
        $participations = Participant::get();

        return view('transactions.edit', ['point' => $point, 'participations' => $participations]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Points $points): RedirectResponse
    {
        $participant = $request->input('participant');
        $points = $request->input('points');
        $reason = $request->input('reason');
        $is_addition = ! empty($request->input('is_addition')) ? true : false;

        Points::where('id', '=', $points)->update([
            'reason' => $reason,
            'points' => $points,
            'is_addition' => $is_addition,
            'FK_PRT' => $participant
        ]);

        return redirect()->back()->with('message', 'Transaktion wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Points $points): RedirectResponse
    {
        Points::where('id', '=', $points)->delete();

        return redirect()->back()->with('message', 'Transaktion erfolgreich gelöscht.');
    }
}
