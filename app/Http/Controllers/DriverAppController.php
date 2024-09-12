<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Driver;
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

        return response()->json([$driver], 200);
    }
}