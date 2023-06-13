<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostRideOffer;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class PostRideOfferController extends Controller
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
            'seats'=>'required',
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date_format:Y-m-d',
            'title'=>'required',
        ]);
        
        // Create a new PostRideOffer instance
        $postRideOffer = new PostRideOffer();
        
        // Set the attributes of the post ride offer    
        $postRideOffer->pickup_loc_latitude = $request->input('pickup_loc_latitude');
        $postRideOffer->pickup_loc_longitude = $request->input('pickup_loc_longitude');
        $postRideOffer->destination_latitude = $request->input('destination_latitude');
        $postRideOffer->destination_longitude = $request->input('destination_longitude');
        $postRideOffer->title = $request->input('title');
        $postRideOffer->passenger_gender = $request->input('passenger_gender');
        $postRideOffer->smoking = filter_var($request->input('smoking'), FILTER_VALIDATE_BOOLEAN);
        $postRideOffer->eating = filter_var($request->input('eating'), FILTER_VALIDATE_BOOLEAN);
        $postRideOffer->manufacturer= $request->input('manufacturer');
        $postRideOffer->model= $request->input('model');
        $postRideOffer->color= $request->input('color');
        $postRideOffer->plates_number= $request->input('plates_number');
        $postRideOffer->seats=$request->input('seats');
        $time = Carbon::createFromFormat('H:i:s', $request->input('time'))->format('H:i:s');
        $postRideOffer->time = $time;
        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'))->format('Y-m-d');
        $postRideOffer->date = $date;
        $postRideOffer->studentID = Auth::id();        
        // Save the post ride offer
        $postRideOffer->save();

        // Return a response 
        return response()->json([
            'message' => 'Post Ride offer created successfully',
            
        ]);
    }

    public function accept($id)
    {
        // Find the post ride offer by ID
        $postRideOffer = PostRideOffer::findOrFail($id);

        // Check if there are available seats
        if ($postRideOffer->seats > 0) {
           // Check if the offer has already been accepted by the current passenger
        $passengerID = Auth::id();
        if ($postRideOffer->passengers()->where('post_ride_offer_id', $id)->where('passenger_id', $passengerID)->exists()) {
            return response()->json([
                'message' => 'You have already accepted this offer.',
            ]);
        }
        

            // Reduce the number of available seats by 1
            $postRideOffer->seats -= 1;
            $postRideOffer->save();

            // Associate the offer with the current passenger
            $postRideOffer->passengers()->attach($passengerID);

            // Get the driver details
        $driverID = $postRideOffer->studentID;
        $driver = Student::findOrFail($driverID);

        // Get the passenger details
        
        $passengerDetails = Student::findOrFail($passengerID);


        $responseData = [
            'message' => 'Post ride offer accepted successfully',
            'passenger' => [
                'first_name' => $passengerDetails->first_name,
                'last_name' => $passengerDetails->last_name,
                'phone' => $passengerDetails->phone,
            ],
            'driver' => [
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'phone' => $driver->phone,
            ],
        ];

        // Return a response
        return response()->json($responseData);


        } else {
            // No available seats
            return response()->json([
                'message' => 'no available seats',
                
            ]);
        }
    }
}
