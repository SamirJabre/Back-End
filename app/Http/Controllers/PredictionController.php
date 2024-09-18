<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PredictionController extends Controller
{
    public function getPrediction(Request $request)
    {
        // Collect the necessary input data from the request
        $data = [
            'departure_hour' => $request->input('departure_hour'),
            'departure_minute' => $request->input('departure_minute'),
            'from_Tripoli' => $request->input('from_Tripoli', 0),
            'from_Anfeh' => $request->input('from_Anfeh', 0),
            'from_Chekka' => $request->input('from_Chekka', 0),
            'from_Batroun' => $request->input('from_Batroun', 0),
            'from_Jbeil' => $request->input('from_Jbeil', 0),
            'from_Tabarja' => $request->input('from_Tabarja', 0),
            "from_Jounieh" => $request->input('from_Jounieh', 0),
            "from_Antelias" => $request->input('from_Antelias', 0),
            "from_Beirut" => $request->input('from_Beirut', 0),
            'to_Tripoli' => $request->input('to_Tripoli', 0),
            'to_Anfeh' => $request->input('to_Anfeh', 0),
            'to_Chekka' => $request->input('to_Chekka',0),
            'to_Batroun' => $request->input('to_Batroun', 0),
            'to_Jbeil' => $request->input('to_Jbeil', 0),
            'to_Tabarja' => $request->input('to_Tabarja', 0),
            'to_Jounieh' => $request->input('to_Jounieh', 0),
            'to_Antelias' => $request->input('to_Antelias', 0),
            'to_Beirut' => $request->input('to_Beirut',0)
        ];

        // Make a POST request to the FastAPI service
        $response = Http::post('http://localhost:8000/predict', $data);

        // Check if the FastAPI service returned a successful response
        if ($response->successful()) {
            // Return the prediction result to the client
            return response()->json([
                'predicted_arrival_time' => $response->json('predicted_arrival_time')
            ]);
        } else {
            // Handle errors
            return response()->json([
                'error' => 'Failed to get prediction from FastAPI'
            ], 500);
        }
    }
}
