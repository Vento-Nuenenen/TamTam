<?php

namespace App\Http\Controllers;

use App\Models\Kid;
use App\Models\Points;
use DB;
use Illuminate\Http\Request;

class CurrentPointsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->input('search') == null) {
            $kids = DB::select('SELECT kids.*, points.*, GROUP_CONCAT(points.points) AS points,
				GROUP_CONCAT(points.is_addition) AS additions FROM `kids`
  			    LEFT JOIN `points` ON `points`.`kid_id` = `kids`.`id` GROUP BY kids.id;');
        } else {
            $search_string = $request->input('search');

            $kids = DB::select("SELECT kids.*, points.*, GROUP_CONCAT(points.points) AS points,
				GROUP_CONCAT(points.is_addition) AS additions FROM `kids`
  			    LEFT JOIN `points` ON `points`.`kid_id` = `kids`.`id`
  			     WHERE scout_name LIKE '%$search_string%'
  			     OR last_name LIKE '%$search_string%'
  			     OR first_name LIKE '%$search_string%'
  			     OR barcode LIKE '%$search_string%'
  			      GROUP BY kids.id;");
        }

        foreach ($kids as $kid) {
            $balance = 0;

            if (! empty($kid->points) || ! empty($kid->additions)) {
                $points = explode(',', $kid->points);
                $additions = explode(',', $kid->additions);

                for ($i = 0; $i < count($points); $i++) {
                    if ($additions[$i] == 1) {
                        $balance += $points[$i];
                    } else {
                        $balance -= $points[$i];
                    }
                }
            }

            $kid->current_balance = $balance;
        }

        return view('points.points', ['kids' => $kids]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kids = Kid::all();

        return view('points.add', ['kids' => $kids]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kid = $request->input('kid');
        $points = $request->input('points');
        $reason = $request->input('reason');
        $is_addition = ! empty($request->input('is_addition')) ? true : false;

        Points::create([
           'reason' => $reason,
           'points' => $points,
           'is_addition' => $is_addition,
           'FK_KID' => $kid
        ]);

        return redirect()->back()->with('message', 'Transaktion wurde erstellt.');
    }
}
