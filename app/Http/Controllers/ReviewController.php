<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function driverReviews(Request $request)
{
    $id = $request->input('id');

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
}
