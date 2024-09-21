<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function driverReviews(Request $request)
{
    $id = $request->input('id');

    $token = $request->header('Authorization');
    if (!$token) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $reviews = DB::table('reviews')
        ->join('users', 'reviews.user_id', '=', 'users.id')
        ->select(
            'reviews.id', 
            'reviews.rating', 
            'reviews.comment', 
            'reviews.created_at', 
            'users.name as user_name',
            'users.profile_picture as user_profile_picture'
        )
        ->where('reviews.driver_id', $id)
        ->get();

    return response()->json($reviews);
}
public function createReview(Request $request)
    {
        $driver_id = $request->input('driver_id');
        $user_id = $request->input('user_id');
        $comment = $request->input('comment');
        $rating = $request->input('rating');

        // Validate inputs
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer|exists:drivers,id',
            'user_id' => 'required|integer|exists:users,id',
            'comment' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Insert the new review
        DB::table('reviews')->insert([
            'driver_id' => $driver_id,
            'user_id' => $user_id,
            'comment' => $comment,
            'rating' => $rating,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Review created successfully'], 201);
    }
    public function getDriverId(Request $request){
        $bus_id = $request->input('bus_id');
        $driver_id = DB::table('buses')
            ->where('id', $bus_id)
            ->value('driver_id');

        return response()->json(['driver_id' => $driver_id]);
    }
}
