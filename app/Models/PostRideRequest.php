<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class PostRideRequest extends Model
{
    
    protected $table='post_ride_request';

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
        'driver_gender',
        'studentID',
        
        
    ];

    
    
    protected $dates=['date','time'];

    public function student()
{
    return $this->belongsTo(Student::class, 'studentID', 'stu_id');
}

public function driver()
    {
        return $this->belongsToMany(Student::class, 'post_ride_request_driver', 'post_ride_request_id', 'driver_id')
            ->withTimestamps();
    }


}
