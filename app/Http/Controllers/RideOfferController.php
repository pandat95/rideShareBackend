<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Student;
use App\Models\RideRequest;
use Geoly\Geoly;
use Illuminate\Support\Facades\DB;


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
        $pickupLatitude = $RideOffer->pickup_loc_latitude;
        $pickupLongitude = $RideOffer->pickup_loc_longitude;

        $matchingRequests = DB::table('ride_request')
            ->join('student', 'ride_request.studentID', '=', 'student.stu_id')
            ->select('ride_request.*', 'student.first_name', 'student.last_name', 'student.phone')
            ->selectRaw("(6371 * acos(cos(radians($pickupLatitude)) * cos(radians(ride_request.pickup_loc_latitude)) * cos(radians(ride_request.pickup_loc_longitude) - radians($pickupLongitude)) + sin(radians($pickupLatitude)) * sin(radians(ride_request.pickup_loc_latitude)))) AS distance")
            ->having('distance', '<=', 2) // 2km radius
            ->where(function ($query) use ($RideOffer) {
                $query->where('student.gender', '=', $RideOffer->passenger_gender);
                    
            })
            ->where('ride_request.smoking', '=', $RideOffer->smoking)
            ->where('ride_request.eating', '=', $RideOffer->eating)
            ->get();

            $matchingRequestArray = [];

        foreach ($matchingRequests as $matchingRequest) {
            $id = $matchingRequest->id;
            
            $studentID = $matchingRequest->studentID;
    
            $matchingRequestArray[] = [
                'id' => $id, 
                'studentID' => $studentID
            ];
        }
    
        return response()->json($matchingRequestArray, 200);






    // $matchingRequests = RideRequest::select('ride_request.*', 'student.first_name', 'student.last_name', 'student.phone', 'ride_request.pickup_loc_latitude', 'ride_request.pickup_loc_longitude')
    // ->join('student', 'ride_request.studentID', '=', 'student.stu_id')
    // ->whereRaw('ST_Distance_Sphere(
    //     POINT(?, ?),
    //     POINT(ride_request.pickup_loc_latitude, ride_request.pickup_loc_longitude)
    // ) <= 2000', [$RideOffer->pickup_loc_latitude, $RideOffer->pickup_loc_longitude])
    // ->where('student.gender', '=', $RideOffer->passenger_gender||$RideOffer->passenger_gender='No specific')
    // ->where('ride_request.smoking', '=', $RideOffer->smoking)
    // ->where('ride_request.eating', '=', $RideOffer->eating)
    // ->get();

// Perform further actions with the matching requests
// foreach ($matchingRequests as $matchingRequest) {
//     // Attach student ID from the ride request table to the ride offer
//     $RideOffer->rideOffers()->attach($matchingRequest->id);

//     // Return a response or redirect as needed
//     return response()->json([
//         'message' => 'Ride Request created successfully',
//         'rideRequestData' => [
//             'first_name' => $matchingRequest->first_name,
//             'last_name' => $matchingRequest->last_name,
//             'phone' => $matchingRequest->phone,
//             // Add other relevant data here
//         ],
//         'rideOfferData' => [
//             'first_name' => $RideOffer->student->first_name,
//             'last_name' => $RideOffer->student->last_name,
//             'phone' => $RideOffer->student->phone,
//             'color'=>$RideOffer->color,
//             // Add other relevant data here
//         ],
//     ]);
// }
//                 // No matches found based on the rules
//                 return response()->json([
//                     'message' => 'No matches found based on the specified rules',
//                 ]);
            }
    
    }

