<?php

namespace App\Http\Controllers;

use App\Models\EmergencyNumber;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmergencyNumbersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $numbers = EmergencyNumber::orderBy('order', 'ASC')->get();

        return view('numbers.numbers', ['numbers' => $numbers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        return view('numbers.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $name = $request->input('name');
        $number = $request->input('number');

        EmergencyNumber::create([
            'name' => $name,
            'number' => $number
        ]);

        return redirect()->back()->with('message', 'Nummer wurde erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmergencyNumber $emergencyNumber): View|Application|Factory
    {
        $number = EmergencyNumber::where('id', '=', $emergencyNumber)->first();

        return view('numbers.edit', ['number' => $number]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmergencyNumber $emergencyNumber): RedirectResponse
    {
        $name = $request->input('name');
        $number = $request->input('number');

        EmergencyNumber::where('id', '=', $emergencyNumber)->update([
            'name' => $name,
            'number' => $number
        ]);

        return redirect()->back()->with('message', 'Nummer wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmergencyNumber $emergencyNumber): RedirectResponse
    {
        EmergencyNumber::where('id', '=', $emergencyNumber)->delete();

        return redirect()->back()->with('message', 'Nummer erfolgreich gelöscht.');
    }

    public function sort(Request $request)
    {
        $numbers = EmergencyNumber::all();

        foreach ($numbers as $number) {
            foreach ($request->order as $order) {
                if (array_key_exists('id', $order) && $order['id'] == $number->id) {
                    $number->update(['order' => $order['position']]);
                }
            }
        }

        return response('Update Successful', 200);
    }
}
