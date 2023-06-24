<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\RideOffer;

class RideRequest extends Model
{
    
    protected $table='ride_request';

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
        
    ];

    
    public $incrementing = false;
    

public function student()
{
    return $this->belongsTo(Student::class, 'studentID', 'stu_id');
}
public function rideOffers()
{
    return $this->belongsToMany(RideOffer::class, 'ride_offer_ride_request', 'ride_request_id', 'ride_offer_id');
}
public function driver()
{
    return $this->belongsToMany(Student::class,'ride_request_student', 'ride_request_id', 'student_id');
}
    
}
