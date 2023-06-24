<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\RideRequest;


class RideOffer extends Model
{
    
    protected $table='ride_offer';

    protected $fillable=
    [

        'smoking',
        'eating',
        'pickup_loc_latitude',
        'pickup_loc_longitude',
        'destination_latitude',
        'destination_longitude',
        'passenger_gender',
        'studentID',
        'manufacturer',
        'model',
        'color',
        'plates_number',
    ];
   

    
    public $incrementing = false;
    

public function student()
{
    return $this->belongsTo(Student::class, 'studentID', 'stu_id');
}

public function rideRequestStudents()
{
    return $this->belongsToMany(RideRequest::class, 'ride_offer_ride_request', 'ride_offer_id', 'ride_request_id');
}
public function passenger()
{
    return $this->belongsToMany(Student::class,'ride_offer_student', 'ride_offer_id', 'student_id');
}
    
}
