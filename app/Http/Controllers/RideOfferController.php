<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

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
        return response()->json([
            'message' => 'Ride offer created successfully',
            
        ]);
        // Search for ride requests within a two-kilometer radius
        /*$matchingRequests = DB::table('_ride_request')
            ->select('_ride_request.*')
            ->join('student', '_ride_request.passenger_id', '=', 'student.stu_id')
            ->where('student.is_driver', false) // Only search for student ride requests
            ->whereRaw("ST_Distance_Sphere(
                POINT(_ride_request.pickup_loc_longitude, _ride_request.pickup_loc_latitude),
                POINT(?, ?)
            ) <= 2000", [$RideOffer->pickup_loc_longitude, $RideOffer->pickup_loc_latitude])
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
            
                    // Retrieve the ride offer's attributes
                    $offerSmoking = $RideOffer->smoking;
                    $offerEating = $RideOffer->eating;
                    $offerPassengerGender = $RideOffer->passenger_gender;
            
                    // Check if the rules match
                    if ($requestSmoking === $offerSmoking &&
                        $requestEating === $offerEating &&
                        $requestPassengerGender === $offerPassengerGender
                    ) {
                        // Associate the ride offer with the matched ride request
                        $matchingRequest->RideOffers()->attach($RideOffer);
            
                        // Return a response with the matched details
            return response()->json([
                'message' => 'Ride offer created and matched with a ride request',
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
