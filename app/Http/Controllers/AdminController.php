<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\City;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        $token = $admin->createToken('AdminToken')->plainTextToken;
            return response()->json([
                'admin' => $admin,
                'token' => $token,
            ], 200);
        
    }
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
        ], 200);
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }

    public function getCities()
    {
        $cities = City::all();
        return response()->json([
            'cities' => $cities,
        ], 200);
    }
    public function createTrip(Request $request)
    {
        // Validate the request
        $request->validate([
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'date' => 'required|date',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'bus_id' => 'required|integer|exists:buses,id',
        ]);

        // List of cities with their IDs and names
        $cities = [
            1 => 'Tripoli',
            2 => 'Anfeh',
            3 => 'Chekka',
            4 => 'Batroun',
            5 => 'Jbeil',
            6 => 'Tabarja',
            7 => 'Jounieh',
            8 => 'Antelias',
            9 => 'Beirut',
        ];

        // Generate the route based on the from and to city IDs
        $fromId = array_search($request->from, $cities);
        $toId = array_search($request->to, $cities);

        if ($fromId === false || $toId === false || $fromId >= $toId) {
            return response()->json([
                'message' => 'Invalid route',
            ], 400);
        }

        $route = [];
        for ($i = $fromId; $i <= $toId; $i++) {
            $route[] = ['id' => $i, 'name' => $cities[$i]];
        }

        // Create a new trip
        $trip = Trip::create([
            'from' => $request->from,
            'to' => $request->to,
            'date' => $request->date,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'price' => $request->price,
            'bus_id' => $request->bus_id,
            'routes' => json_encode($route), // Include routes as JSON
        ]);

        // Return a success response with the created trip details
        return response()->json([
            'message' => 'Trip created successfully',
            'trip' => $trip,
        ], 201);
    }

}