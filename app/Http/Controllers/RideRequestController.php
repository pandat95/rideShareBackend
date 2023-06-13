<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\RideOffer;

class RideRequestController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'pickup_loc_latitude' => 'required',
            'pickup_loc_longitude' => 'required',
            'destination_latitude' => 'required',
            'destination_longitude' => 'required',
            'driver_gender'=>'required',
            'smoking'=>'required|boolean',
            'eating'=>'required|boolean',
            'pickup_loc_latitude' => 'required|numeric|between:-90,90',
            'pickup_loc_longitude' => 'required|numeric|between:-180,180',
            
            
        ]);

        // Create a new RideRequest instance
        $RideRequest = new RideRequest();
        
        // Set the attributes of the  ride Request
        $RideRequest->pickup_loc_latitude = $request->input('pickup_loc_latitude');
        $RideRequest->pickup_loc_longitude = $request->input('pickup_loc_longitude');
        $RideRequest->destination_latitude = $request->input('destination_latitude');
        $RideRequest->destination_longitude = $request->input('destination_longitude');
        $RideRequest->driver_gender = $request->input('driver_gender');
        $RideRequest->smoking = filter_var($request->input('smoking'), FILTER_VALIDATE_BOOLEAN);
        $RideRequest->eating = filter_var($request->input('eating'), FILTER_VALIDATE_BOOLEAN);
    
        $RideRequest->studentID = Auth::id();        
        // Save the  ride Request
        $RideRequest->save();

         // Match ride requests within a two-kilometer radius
    $matchingRequests = RideOffer::select('ride_offer.*', 'student.first_name', 'student.last_name', 'student.phone', 'ride_offer.pickup_loc_latitude', 'ride_offer.pickup_loc_longitude')
    ->join('student', 'ride_offer.studentID', '=', 'student.stu_id')
    ->whereRaw('ST_Distance_Sphere(
        POINT(?, ?),
        POINT(ride_offer.pickup_loc_latitude, ride_offer.pickup_loc_longitude)
    ) <= 2000', [$RideRequest->pickup_loc_latitude, $RideRequest->pickup_loc_longitude])
    ->where('student.gender', '=', $RideRequest->driver_gender)
    ->where('ride_offer.smoking', '=', $RideRequest->smoking)
    ->where('ride_offer.eating', '=', $RideRequest->eating)
    ->get();

// Perform further actions with the matching requests
foreach ($matchingRequests as $matchingRequest) {
    // Attach student ID from the ride request table to the ride offer
    $RideRequest->rideOffers()->attach($matchingRequest->ride_offer_id);

    // Send information to the student from the ride offer
    // ...

    // Send information to the student from the ride request
    $rideOfferData = [
        'first_name' => $matchingRequest->first_name,
        'last_name' => $matchingRequest->last_name,
        'phone' => $matchingRequest->phone,
        'manufacturer' => $matchingRequest->manufacturer,
        'model' => $matchingRequest->model,
        'color' => $matchingRequest->color,
        'plates_number' => $matchingRequest->plates_number,
        'live_tracking_location' => $matchingRequest->pickup_loc_latitude . ',' . $matchingRequest->pickup_loc_longitude
    ];

    $rideRequestData = [
        'first_name' => $RideRequest->student->first_name,
        'last_name' => $RideRequest->student->last_name,
        'phone' => $RideRequest->student->phone,
        'location' => $RideRequest->pickup_loc_latitude . ',' . $RideRequest->pickup_loc_longitude
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
