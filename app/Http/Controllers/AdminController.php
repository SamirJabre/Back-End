<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Application;
use App\Models\Bus;
use App\Models\City;
use App\Models\Driver;
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

    public function getAllApplications()
    {
        $applications = Application::all();
        return response()->json(
            $applications
        , 200);
    }


    public function acceptApplicant(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:15',
            'age' => 'required|integer',
            'profile_picture' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'id_photo' => 'required|string|max:255',
            'driver_license' => 'required|string|max:255',
            'id' => 'required|integer|exists:applications,id',
        ]);

        $driver = new Driver();
        $driver->name = $validatedData['name'];
        $driver->email = $validatedData['email'];
        $driver->password = $validatedData['password'];
        $driver->phone_number = $validatedData['phone_number'];
        $driver->age = $validatedData['age'];
        $driver->profile_picture = $validatedData['profile_picture'];
        $driver->address = $validatedData['address'];
        $driver->id_photo = $validatedData['id_photo'];
        $driver->driver_license = $validatedData['driver_license'];
        $driver->save();

        Application::destroy($validatedData['id']);

        return response()->json(['message' => 'Driver application Approved'], 201);
    }


    public function rejectApplicant(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:applications,id',
        ]);

        Application::destroy($validatedData['id']);

        return response()->json(['message' => 'Driver application Rejected'], 201);
    }

    public function getBuses()
    {
        $buses = Bus::all();
        return response()->json([
            'buses' => $buses,
        ], 200);
    }

    public function getDrivers()
    {
        $drivers = Driver::all();
        return response()->json(
        $drivers
        , 200);
    }

    public function assignDriverToBus(Request $request)
{
    // Validate the input
    $validatedData = $request->validate([
        'driver_id' => 'required|exists:drivers,id',
        'bus_id' => 'required|exists:buses,id',
    ]);

    // Find the bus by its ID
    $bus = Bus::find($validatedData['bus_id']);

    // Assign the driver to the bus
    $bus->driver_id = $validatedData['driver_id'];
    $bus->save();

    // Return a success response
    return response()->json([
        'message' => 'Driver assigned to bus successfully',
        'bus' => $bus,
    ], 200);
}

}