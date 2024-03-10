<?php

namespace App\Http\Controllers;

use App\Helpers\Barcode;
use App\Models\Group;
use App\Models\Kid;
use Illuminate\Http\Request;

class KidsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->input('search') == null) {
            $kids = Kid::all();
        } else {
            $search_string = $request->input('search');
            $kids = Kid::leftJoin('groups', 'groups.id', '=', 'kids.FK_GID')
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
    public function create()
    {
        $groups = Group::all();

        return view('kids.add', ['groups' => $groups]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $barcode = Barcode::generateBarcode();

        if($request->file('tn_img')) {
            $img_name = 'tnimg_'.time().'.'.$request->file('tn_img')->extension();
            $request->file('tn_img')->move(storage_path('app/public/img'), $img_name);
        } else {
            $img_name = null;
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

        Kid::create([
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
            'person_picture' => $img_name,
        ]);

        return redirect()->back()->with('message', 'Teilnehmer wurde erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kid $kid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kid $kid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kid $kid)
    {
        //
    }
}
