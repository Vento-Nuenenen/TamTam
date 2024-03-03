<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Participant;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $participations = Participant::all();
        $items = Item::all();

        return view('sales.sales', ['participations' => $participations, 'items' => $items]);
    }

    /**
     * Lookup EAN Codes.
     */
    public function lookup(Request $request): Response|ResponseFactory|Application
    {
        $item = Item::where('item_barcode', '=', $request->ean)->get();

        return response($item, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        print_r($request->input());
    }
}
