<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Driver;
use App\Models\Trip;
use Illuminate\Http\Request;

class DriverAppController extends Controller
{
    public function createDriverApplication(Request $request)
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
        ]);

        $driverApplication = new Application();
        $driverApplication->name = $validatedData['name'];
        $driverApplication->email = $validatedData['email'];
        $driverApplication->password = $validatedData['password'];
        $driverApplication->phone_number = $validatedData['phone_number'];
        $driverApplication->age = $validatedData['age'];
        $driverApplication->profile_picture = $validatedData['profile_picture'];
        $driverApplication->address = $validatedData['address'];
        $driverApplication->id_photo = $validatedData['id_photo'];
        $driverApplication->driver_license = $validatedData['driver_license'];
        $driverApplication->save();

        return response()->json(['message' => 'Driver application created successfully'], 201);
    }
    
    public function driverLogin(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $driver = Driver::where('email', $validatedData['email'])->first();

        if (!$driver || $driver->password !== $validatedData['password']) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json($driver, 200);
    }

    public function getTripsByDriverId(Request $request)
{
    // Validate the driver_id from the request
    $validatedData = $request->validate([
        'driver_id' => 'required|integer|exists:drivers,id',
    ]);

    // Retrieve trips by joining trips and buses tables
    $trips = Trip::join('buses', 'trips.bus_id', '=', 'buses.id')
                 ->where('buses.driver_id', $validatedData['driver_id'])
                 ->select('trips.*')
                 ->get();

    // Return the trips in a JSON response
    return response()->json($trips, 200);
}

}