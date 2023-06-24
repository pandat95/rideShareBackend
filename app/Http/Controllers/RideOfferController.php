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
            ->where('student.stu_id','!=',$RideOffer->studentID)
            ->get();

            $matchingRequestArray = [];

        foreach ($matchingRequests as $matchingRequest) {
            $id = $matchingRequest->id;
            $studentID = $matchingRequest->studentID;
            $firstName = student::where('stu_id','=', $studentID)->value('first_name');
            $lastName = student::where('stu_id','=', $studentID)->value('last_name');
            $pickupLat=$matchingRequest->pickup_loc_latitude;
            $pickupLong=$matchingRequest->pickup_loc_longitude;
            $destLat=$matchingRequest->destination_latitude;
            $destLong=$matchingRequest->destination_longitude;
            $Phone = student::where('stu_id','=', $studentID)->value('phone');
            $car=$RideOffer->manufacturer;
            $color=$RideOffer->color;
            $model=$RideOffer->model;
            $plates_number=$RideOffer->plates_number;
    
            $matchingRequestArray[] = [
                'id' => $id, 
                'studentID' => $studentID,
                'FName'=>$firstName,
                'LName'=>$lastName,
                'pickupLat'=>$pickupLat,
                'pickupLong'=>$pickupLong,
                'destLat'=>$destLat,
                'destLong'=>$destLong,
                'carCompany'=>$car,
                'model'=>$model,
                'color'=>$color,
                'platesNumber'=>$plates_number,
                'phone'=>$Phone
            ];
        }
    
        return response()->json($matchingRequestArray, 200);
        //return response()->json('did it work',200);


            }
            public function accept($id)
            {
                $RideOffer= RideOffer::findOrFail($id);
                if ($RideOffer->passenger()->exists()) {
                    return response()->json([
                        'message' => 'This offer has already been accepted.',
                    ]);
                }
            
                $passengerID= Auth::id();
                $RideOffer->passenger()->attach($passengerID);
                $passengerDetails = Student::findOrFail($passengerID);
                $driverID = $RideOffer->studentID;
                $driver = Student::findOrFail($driverID);
                return response()->json('Ride Offer Accepted Successfully', 200);
            
            
            }
    }

