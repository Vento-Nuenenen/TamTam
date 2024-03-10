<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kid $kid)
    {
        //
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
