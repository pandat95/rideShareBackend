<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RideRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Student;
use App\Models\RideOffer;
use Geoly\Geoly;
use Illuminate\Support\Facades\DB;

class RideRequestController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            
            'driver_gender'=>'required',
            'smoking'=>'required|boolean',
            'eating'=>'required|boolean',
            'pickup_loc_latitude' => 'required|numeric|between:-90,90',
            'pickup_loc_longitude' => 'required|numeric|between:-180,180',
            'destination_latitude' => 'required|numeric|between:-90,90',
            'destination_longitude' => 'required|numeric|between:-180,180',
            
            
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

        
                // No matches found based on the rules
                // return response()->json([
                //     'message' => 'Ride Request created successfully',
                // ]);
                $pickupLatitude = $RideRequest->pickup_loc_latitude;
        $pickupLongitude = $RideRequest->pickup_loc_longitude;
            
    $matchingRequests = DB::table('ride_offer')
    ->join('student', 'ride_offer.studentID', '=', 'student.stu_id')
    ->select('ride_offer.*', 'student.first_name', 'student.last_name', 'student.phone')
    ->selectRaw("(6371 * acos(cos(radians($pickupLatitude)) * cos(radians(ride_offer.pickup_loc_latitude)) * cos(radians(ride_offer.pickup_loc_longitude) - radians($pickupLongitude)) + sin(radians($pickupLatitude)) * sin(radians(ride_offer.pickup_loc_latitude)))) AS distance")
    ->having('distance', '<=', 2) // 2km radius
    ->where(function ($query) use ($RideRequest) {
        $query->where('student.gender', '=', $RideRequest->driver_gender);
            
    })
    ->where('ride_offer.smoking', '=', $RideRequest->smoking)
    ->where('ride_offer.eating', '=', $RideRequest->eating)
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

        
}
}