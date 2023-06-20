<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PostRideRequest;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class PostRideRequestController extends Controller
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
            // 'time' => 'required|date_format:H:i:s',
            // 'date' => 'required|date_format:Y-m-d',
            'title'=>'required',
            'Subtitle'=>'required',
            'DateTime'=>'required',

    

            
            
        ]);
        

        // Create a new PostRideRequest instance
        $postRideRequest = new PostRideRequest();
        
        // Set the attributes of the post ride Request
    
        $postRideRequest->pickup_loc_latitude = $request->input('pickup_loc_latitude');
        $postRideRequest->pickup_loc_longitude = $request->input('pickup_loc_longitude');
        $postRideRequest->destination_latitude = $request->input('destination_latitude');
        $postRideRequest->destination_longitude = $request->input('destination_longitude');
        $postRideRequest->title = $request->input('title');
        $postRideRequest->Subtitle = $request->input('Subtitle');
        $postRideRequest->driver_gender = $request->input('driver_gender');
        $postRideRequest->smoking = filter_var($request->input('smoking'), FILTER_VALIDATE_BOOLEAN);
        $postRideRequest->eating = filter_var($request->input('eating'), FILTER_VALIDATE_BOOLEAN);
        // $time = Carbon::createFromFormat('H:i:s', $request->input('time'))->format('H:i:s');
        // $postRideRequest->time = $time;
        // $date = Carbon::createFromFormat('Y-m-d', $request->input('date'))->format('Y-m-d');
        // $postRideRequest->date = $date;
        $carbonDateTime = Carbon::parse($request->input('DateTime'));
        $formattedDateTime = $carbonDateTime->format('Y-m-d H:i:s');
        $postRideRequest->DateTime = $formattedDateTime;
        $postRideRequest->studentID = Auth::id();
        
        
        
        // Save the post ride Request
        $postRideRequest->save();

        // Return a response or redirect as needed
        
        return response()->json([
            'message' => 'Post Ride Request created successfully',
            
        ]);
    }

    public function accept($id)
    {
        // Find the post ride Request by ID
        $postRideRequest = PostRideRequest::findOrFail($id);


        
          // Check if the Request has already been accepted by any driver
    if ($postRideRequest->driver()->exists()) {
        return response()->json([
            'message' => 'This request has already been accepted.',
        ]);
    }

    // Get the authenticated driver ID
    $driverID = Auth::id();

    // Associate the Request with the current driver
    $postRideRequest->driver()->attach($driverID);

    // Get the driver details
    $driverDetails = Student::findOrFail($driverID);

    // Get the passenger details
    $passengerID = $postRideRequest->studentID;
    $passenger = Student::findOrFail($passengerID);

    $responseData = [
        'message' => 'Post ride request accepted successfully',
        'driver' => [
            'first_name' => $driverDetails->first_name,
            'last_name' => $driverDetails->last_name,
            'phone' => $driverDetails->phone,
        ],
        'passenger' => [
            'first_name' => $passenger->first_name,
            'last_name' => $passenger->last_name,
            'phone' => $passenger->phone,
        ],
    ];

    // Return the response
    return response()->json($responseData);


        } 
    }

