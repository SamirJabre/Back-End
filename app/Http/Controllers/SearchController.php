<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchTrips(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $price = $request->input('price');
        $date = $request->input('date');

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
        if ($date) {
            $query->where('date', $date);
        }

        $trips = $query->get();

        return response()->json($trips);
    }

    public function trips()
    {
        $trips = DB::table('trips')
        ->join('buses', 'trips.bus_id', '=', 'buses.id')
        ->join('drivers', 'buses.driver_id', '=', 'drivers.id')
        ->select('trips.id','trips.date','trips.departure_time','trips.arrival_time','trips.from','trips.to','trips.price', 'buses.passenger_load', 'drivers.name', 'drivers.rating')
        ->get();

    return $trips;
    }

    public function tripById(Request $request)
{
    $id = $request->input('id');

    $trip = DB::table('trips')
        ->join('buses', 'trips.bus_id', '=', 'buses.id')
        ->join('drivers', 'buses.driver_id', '=', 'drivers.id')
        ->select('trips.id', 'trips.date', 'trips.departure_time', 'trips.arrival_time', 'trips.from', 'trips.to', 'trips.price', 'buses.passenger_load', 'drivers.name', 'drivers.rating')
        ->where('trips.id', $id)
        ->first();

    return response()->json($trip);
}
}
