<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

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

    
}
