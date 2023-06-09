<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class PostRideOffer extends Model
{

    protected $table='post_ride_offer';

    protected $fillable=
    [
        'title',
        'date',
        'time',
        'smoking',
        'eating',
        'pickup_loc_latitude',
        'pickup_loc_longitude',
        'destination_latitude',
        'destination_longitude',
        'passenger_gender',
        'seats',
        'studentID',
        'passengerID',
        'manufacturer',
        'model',
        'color',
        'plates_number',
    ];

    
    
    protected $dates=['date','time'];

public function student()
{
    return $this->belongsTo(Student::class, 'studentID', 'stu_id');
}

public function passenger()
{
    return $this->belongsTo(Student::class, 'passengerID', 'stu_id');
}



}
