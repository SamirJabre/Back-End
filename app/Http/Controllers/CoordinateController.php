<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoordinateController extends Controller
{
    public function index()
    {
        return view('coordinate');
    }

    public function getCoordinates(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $coordinates = DB::table('cities')
            ->select('latitude', 'longitude')
            ->where('city', $from)
            ->orWhere('city', $to)
            ->get();
        return response()->json($coordinates);
    }
}
