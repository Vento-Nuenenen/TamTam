<?php

namespace App\Http\Controllers;

use App\Models\Kid;
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
            $transactions = Points::with(['kid'])->get();
        } else {
            $search_string = $request->input('search');
            $transactions = Points::with('kid')
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
        $kids = Kid::all();

        return view('transactions.add', ['kids' => $kids]);
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

        $response = new Points([
            'reason' => $reason,
            'points' => $points,
            'is_addition' => $is_addition,
        ]);
        $response->kid()->associate($participant)->save();

        return redirect()->back()->with('message', 'Transaktion wurde erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($trid): View|Application|Factory
    {
        $point = Points::find($trid);
        $kids = Kid::get();

        return view('transactions.edit', ['point' => $point, 'kids' => $kids]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Points $points): RedirectResponse
    {
        $kid = $request->input('kid');
        $points = $request->input('points');
        $reason = $request->input('reason');
        $is_addition = ! empty($request->input('is_addition'));

        Points::where('id', '=', $points)->update([
            'reason' => $reason,
            'points' => $points,
            'is_addition' => $is_addition,
            'FK_KID' => $kid
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
