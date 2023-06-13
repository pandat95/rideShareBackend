<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

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

        // Return a response or redirect as needed
        return response()->json([
            'message' => 'Ride Request created successfully',
            
        ]);
        // Search for ride requests within a two-kilometer radius
        /*$matchingRequests = DB::table('_ride_request')
            ->select('_ride_request.*')
            ->join('student', '_ride_request.passenger_id', '=', 'student.stu_id')
            ->where('student.is_driver', false) // Only search for student ride requests
            ->whereRaw("ST_Distance_Sphere(
                POINT(_ride_request.pickup_loc_longitude, _ride_request.pickup_loc_latitude),
                POINT(?, ?)
            ) <= 2000", [$RideRequest->pickup_loc_longitude, $RideRequest->pickup_loc_latitude])
            ->orderBy('_ride_request.created_at', 'asc') // Order by creation time to get the first match
            ->get();

            if ($matchingRequests->isEmpty()) {
                // No matching ride requests found
                return response()->json([
                    'message' => 'No matching ride requests found',
                ]);
            } else {
                // Apply matching rules and perform any other necessary checks
                foreach ($matchingRequests as $matchingRequest) {
                    // Retrieve the matching request's attributes
                    $requestSmoking = $matchingRequest->smoking;
                    $requestEating = $matchingRequest->eating;
                    $requestPassengerGender = $matchingRequest->passenger_gender;
            
                    // Retrieve the ride Request's attributes
                    $RequestSmoking = $RideRequest->smoking;
                    $RequestEating = $RideRequest->eating;
                    $RequestPassengerGender = $RideRequest->passenger_gender;
            
                    // Check if the rules match
                    if ($requestSmoking === $RequestSmoking &&
                        $requestEating === $RequestEating &&
                        $requestPassengerGender === $RequestPassengerGender
                    ) {
                        // Associate the ride Request with the matched ride request
                        $matchingRequest->RideRequests()->attach($RideRequest);
            
                        // Return a response with the matched details
            return response()->json([
                'message' => 'Ride Request created and matched with a ride request',
                'passenger' => [
                    'first_name' => $passengerFirstName,
                    'last_name' => $passengerLastName,
                    'phone' => $passengerPhone,
                    'location' => $passengerLocation,
                    'pickup_loc_latitude' => $matchingRequest->pickup_loc_latitude,
                    'pickup_loc_longitude' => $matchingRequest->pickup_loc_longitude,
                ],
                'driver' => [
                    'first_name' => $driverFirstName,
                    'last_name' => $driverLastName,
                    'phone' => $driverPhone,
                    'vehicle' => [
                        'manufacturer' => $vehicleManufacturer,
                        'model' => $vehicleModel,
                        'color' => $vehicleColor,
                        'plates_number' => $vehiclePlatesNumber,
                    ],
                ],
            ]);
        }
    }
            
                // No matches found based on the rules
                return response()->json([
                    'message' => 'No matches found based on the specified rules',
                ]);
            }*/
    
    }
}
