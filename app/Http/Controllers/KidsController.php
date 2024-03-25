<?php

namespace App\Http\Controllers;

use App\Helpers\Barcode;
use App\Models\Group;
use App\Models\Kid;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KidsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        if($request->input('search') == null) {
            $kids = Kid::with(['group'])->get();
        } else {
            $search_string = $request->input('search');
            $kids = Kid::with(['group'])
                ->select('kids.*', 'groups.group_name')
                ->where('scout_name', 'LIKE', "%$search_string%")
                ->orWhere('last_name', 'LIKE', "%$search_string%")
                ->orWhere('first_name', 'LIKE', "%$search_string%")
                ->orWhere('group_name', 'LIKE', "%$search_string%")
                ->orWhere('barcode', 'LIKE', "%$search_string%")->get();
        }

        return view('kids.kids', ['kids' => $kids]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $groups = Group::all();

        return view('kids.add', ['groups' => $groups]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        print_r($request->file());

        $scout_name = $request->input('scout_name');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $address = $request->input('address');
        $plz = $request->input('plz');
        $place = $request->input('place');
        $birthday = $request->input('birthday');
        $gender = $request->input('gender');
        $group = $request->input('group');
        $barcode = Barcode::generateBarcode();

        if($request->file('image')) {
            $image = 'tnimg_'.time().'.'.$request->file('image')->extension();
            $request->file('image')->move(storage_path('app/public/img'), $image);
        } else {
            $image = null;
        }

        if($gender) {
            if($gender == 'm') {
                $gender = 'Männlich';
            } elseif($gender == 'w') {
                $gender = 'Weiblich';
            } elseif($gender == 'd') {
                $gender = 'Anderes';
            } else {
                $gender = null;
            }
        } else {
            $gender = null;
        }

        $kid = new Kid([
            'scout_name' => $scout_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'barcode' => $barcode,
            'address' => $address,
            'plz' => $plz,
            'place' => $place,
            'birthday' => $birthday,
            'gender' => $gender,
            'group_id' => $group,
            'image' => $image,
        ]);
        $kid->group()->associate($group)->save();

        return redirect()->back()->with('message', 'Teilnehmer wurde erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kid)
    {
        $kid = Kid::find($kid);
        $groups = Group::all();

        return view('kids.edit', ['kid' => $kid, 'groups' => $groups]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kid)
    {
        $scout_name = $request->input('scout_name');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $address = $request->input('address');
        $plz = $request->input('plz');
        $place = $request->input('place');
        $birthday = $request->input('birthday');
        $gender = $request->input('gender');
        $group = $request->input('group');
        $barcode = $request->input('barcode');

        if($request->file('image')) {
            $image = 'tnimg_'.time().'.'.$request->file('image')->extension();
            $request->file('image')->move(storage_path('app/public/img'), $image);
        } else {
            $image = null;
        }

        if($gender) {
            if($gender == 'm') {
                $gender = 'Männlich';
            } elseif($gender == 'w') {
                $gender = 'Weiblich';
            } elseif($gender == 'd') {
                $gender = 'Anderes';
            } else {
                $gender = null;
            }
        } else {
            $gender = null;
        }

        $kid = Kid::find($kid);
        $kid->fill([
            'scout_name' => $scout_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'barcode' => $barcode,
            'address' => $address,
            'plz' => $plz,
            'place' => $place,
            'birthday' => $birthday,
            'gender' => $gender,
            'image' => $image,
        ]);
        $kid->group()->associate($group)->save();

        return redirect()->back()->with('message', 'Teilnehmer wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kid): RedirectResponse
    {
        Kid::destroy($kid);

        return redirect()->back()->with('message', 'TN erfolgreich gelöscht.');
    }
}
