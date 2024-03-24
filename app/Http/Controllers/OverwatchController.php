<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Kid;
use DB;
use Illuminate\Http\Request;

class OverwatchController extends Controller
{
    public function index(Request $request)
    {
        if($request->barcode != null) {
            $barcode = $request->barcode;

            $kids = DB::select("SELECT kids.*, points.*, groups.*, GROUP_CONCAT(points.points) AS points,
				GROUP_CONCAT(points.is_addition) AS additions FROM `kids`
  			    LEFT JOIN `points` ON `points`.`FK_PRT` = `kids`.`id`
  			    LEFT JOIN `groups` ON `participations`.`FK_GRP` = `groups`.`id` WHERE `kids`.`barcode` LIKE $barcode
 				GROUP BY kids.id;");

            foreach($kids as $kid) {
                $balance = 0;

                if(!empty($kid->points) || !empty($kid->additions)) {
                    $points = explode(',', $kid->points);
                    $additions = explode(',', $kid->additions);

                    for($i = 0; $i < count($points); $i++) {
                        if ($additions[$i] == 1) {
                            $balance += $points[$i];
                        } else {
                            $balance -= $points[$i];
                        }
                    }
                }

                $kid->current_balance = $balance;
            }

            $kids = $kids[0] ?? null;

            return view('overwatch.overwatch', ['kids' => $kids]);
        } else if($request->tableorder != null) {
            $kids = Kid::inRandomOrder()->get();

            $j = 0;

            foreach ($kids as $kid) {
                $j++;
                Kid::where('id', '=', $kid->id)->update([
                    'seat_number' => $j
                ]);
            }

            session()->put('message', 'Tischordnung wurde erfolgreich generiert!');

            return view('overwatch.overwatch');
        } else if($request->grouping != null) {
            $groups = Group::all();
            $groups_count = count($groups);
            $kids = Kid::inRandomOrder()->get();
            $j = 1;

            foreach($kids as $kid) {
                if ($j <= $groups_count) {
                    Kid::where('id', '=', $kid->id)->update(['FK_GRP' => $j]);
                    $j++;
                } else {
                    $j = 1;
                    Kid::where('id', '=', $kid->id)->update(['FK_GRP' => $j]);
                    $j++;
                }
            }

            session()->put('message', 'Gruppen wurden erfolgreich zugeordnet!');

            return view('overwatch.overwatch');
        } else {
            return view('overwatch.overwatch');
        }
    }
}
