<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\RideRequest;


class RideOfferController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'pickup_loc_latitude' => 'required',
            'pickup_loc_longitude' => 'required',
            'destination_latitude' => 'required',
            'destination_longitude' => 'required',
            'passenger_gender'=>'required',
            'smoking'=>'required|boolean',
            'eating'=>'required|boolean',
            'manufacturer'=>'required',
            'model'=>'required',
            'color'=>'required',
            'plates_number'=>'required',
            'pickup_loc_latitude' => 'required|numeric|between:-90,90',
            'pickup_loc_longitude' => 'required|numeric|between:-180,180',
            
            
        ]);

        // Create a new RideOffer instance
        $RideOffer = new RideOffer();
        
        // Set the attributes of the  ride offer
        $RideOffer->pickup_loc_latitude = $request->input('pickup_loc_latitude');
        $RideOffer->pickup_loc_longitude = $request->input('pickup_loc_longitude');
        $RideOffer->destination_latitude = $request->input('destination_latitude');
        $RideOffer->destination_longitude = $request->input('destination_longitude');
        $RideOffer->passenger_gender = $request->input('passenger_gender');
        $RideOffer->smoking = filter_var($request->input('smoking'), FILTER_VALIDATE_BOOLEAN);
        $RideOffer->eating = filter_var($request->input('eating'), FILTER_VALIDATE_BOOLEAN);
        $RideOffer->manufacturer= $request->input('manufacturer');
        $RideOffer->model= $request->input('model');
        $RideOffer->color= $request->input('color');
        $RideOffer->plates_number= $request->input('plates_number');
        $RideOffer->studentID = Auth::id();        
        // Save the  ride offer
        $RideOffer->save();

        // Return a response or redirect as needed
        // return response()->json([
        //     'message' => 'Ride offer created successfully',
            
        // ]);
        // Match ride requests within a two-kilometer radius
    $matchingRequests = RideRequest::select('ride_request.*', 'student.first_name', 'student.last_name', 'student.phone', 'ride_request.pickup_loc_latitude', 'ride_request.pickup_loc_longitude')
    ->join('student', 'ride_request.studentID', '=', 'student.stu_id')
    ->whereRaw('ST_Distance_Sphere(
        POINT(?, ?),
        POINT(ride_request.pickup_loc_latitude, ride_request.pickup_loc_longitude)
    ) <= 2000', [$RideOffer->pickup_loc_latitude, $RideOffer->pickup_loc_longitude])
    ->where('student.gender', '=', $RideOffer->passenger_gender)
    ->where('ride_request.smoking', '=', $RideOffer->smoking)
    ->where('ride_request.eating', '=', $RideOffer->eating)
    ->get();

// Perform further actions with the matching requests
foreach ($matchingRequests as $matchingRequest) {
    // Attach student ID from the ride request table to the ride offer
    $RideOffer->rideRequestStudents()->attach($matchingRequest->ride_request_id);

    // Send information to the student from the ride offer
    // ...

    // Send information to the student from the ride request
    $rideOfferData = [
        'first_name' => $RideOffer->student->first_name,
        'last_name' => $RideOffer->student->last_name,
        'phone' => $RideOffer->student->phone,
        'manufacturer' => $RideOffer->manufacturer,
        'model' => $RideOffer->model,
        'color' => $RideOffer->color,
        'plates_number' => $RideOffer->plates_number,
        'live_tracking_location' => $RideOffer->pickup_loc_latitude . ',' . $RideOffer->pickup_loc_longitude
    ];

    $rideRequestData = [
        'first_name' => $matchingRequest->first_name,
        'last_name' => $matchingRequest->last_name,
        'phone' => $matchingRequest->phone,
        'location' => $matchingRequest->pickup_loc_latitude . ',' . $matchingRequest->pickup_loc_longitude
    ];

    // Send the information to the respective students using your preferred method (e.g., email, notification, etc.)
    // ...
}

// Return a response or redirect as needed
return response()->json([
    'message' => 'Ride offer created successfully',
    'rideOfferData' => $rideOfferData,
    'rideRequestData'=> $rideRequestData,
]);
                // No matches found based on the rules
                return response()->json([
                    'message' => 'No matches found based on the specified rules',
                ]);
            }
    
    }

