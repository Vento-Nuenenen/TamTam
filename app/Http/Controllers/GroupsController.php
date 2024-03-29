<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->search == null) {
            $groups = Group::all();
        } else {
            $search_string = $request->search;
            $groups = Group::where('groups.name', 'LIKE', "%$search_string%")->get();
        }

        return view('groups.groups', ['groups' => $groups]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('groups.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $group_name = $request->input('group_name');

        if ($request->file('group_logo')) {
            $logo_name = time().'.'.$request->file('group_logo')->extension();
            $request->file('group_logo')->move(storage_path('app/public/img'), $logo_name);
        } else {
            $logo_name = null;
        }

        Group::create([
            'name' => $group_name,
            'image' => $logo_name
        ]);

        return redirect()->back()->with('message', 'Gruppe wurde erstellt.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($gid)
    {
        $group = Group::find($gid);

        return view('groups.edit', ['group' => $group]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $gid)
    {
        $group_name = $request->input('group_name');

        if ($request->file('group_logo')) {
            $logo_name = time().'.'.$request->file('group_logo')->extension();
            $request->file('group_logo')->move(storage_path('app/public/img'), $logo_name);
        }else{
            $logo_name = null;
        }

        $group = Group::find($gid);
        $group->name = $group_name;

        if($logo_name != null){
            $group->image = $logo_name;
        }

        $group->save();

        return redirect()->back()->with('message', 'Gruppe wurde aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($gid)
    {
        Group::destroy($gid);

        return redirect()->back()->with('message', 'Gruppe erfolgreich gelöscht.');
    }
}
