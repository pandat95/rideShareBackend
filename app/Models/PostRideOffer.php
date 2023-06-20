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
        // 'date',
        // 'time',
        'DateTime',
        'smoking',
        'eating',
        'pickup_loc_latitude',
        'pickup_loc_longitude',
        'destination_latitude',
        'destination_longitude',
        'passenger_gender',
        'seats',
        'studentID',
        'Subtitle',
        'manufacturer',
        'model',
        'color',
        'plates_number',
    ];

    
    
    protected $dates=['DateTime'];

public function student()
{
    return $this->belongsTo(Student::class, 'studentID', 'stu_id');
}

public function passengers()
    {
        return $this->belongsToMany(Student::class, 'post_ride_offer_passenger', 'post_ride_offer_id', 'passenger_id')
            ->withTimestamps();
    }



}