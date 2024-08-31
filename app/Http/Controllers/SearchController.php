<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchTrips(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $price = $request->input('price');
        $rating = $request->input('rating');

        $query = Trip::query();

        if ($from) {
            $query->where('from', $from);
        }

        if ($to) {
            $query->where('to', $to);
        }

        if ($price) {
            $query->where('price', '<=', $price);
        }
        if ($rating) {
            $query->join('drivers', 'trips.driver_id', '=', 'drivers.id')
                  ->where('drivers.rating', '>=', $rating);
        }

        $trips = $query->get();

        return response()->json($trips);
    }
}
